<?php

namespace App\Http\Controllers;

use App\Business\ExamBusiness;
use App\Business\UserBusiness;
use http\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ExamController extends Controller
{
    private $examBusiness;
    private $userBusiness;

    public function __construct()
    {
        $this->middleware('auth');
        $this->examBusiness = new ExamBusiness();
        $this->userBusiness = new UserBusiness();
    }

    public function generate($id) {
        echo "<script>console.log(abc" . $id . ");</script>";
        $examInfo = $this->examBusiness->generate($id);

        $optionsNumber = array();
        for($index = 0; $index < count($examInfo[0]); $index++) {
            $optionsNumber[$index] = $examInfo[0][$index]['exercise']['options']['counter'];
        }

        return view('exam/exam', ['exercises' => $examInfo[0], 'info' => $examInfo[1],
            'optionsNumber' => $optionsNumber, 'examId' => $id]);
    }

    public function correctPartial(Request $request) {

        if($request->ajax()) {
            $studentAnswers = $request->input('answers');
            $exercisesNumber = $request->input('exercisesNr');
            $optionsNumber = $request->input('optionsNr');
            $examId = $request->input('examId');
            $studentAnswers = json_decode($studentAnswers);
            $userId = $this->examBusiness->correct($studentAnswers, $exercisesNumber, $optionsNumber, $examId);
            return $userId;
        }
        else
            return 0;
    }

    public function showResult($id) {
        $subject = $this->examBusiness->getExamResult($id);
        return view('exam/result', ['points' => $subject->points,
            'exercise1' => json_decode($subject->exercise_1, true),
            'exercise2' => json_decode($subject->exercise_2, true),
            'exercise3' => json_decode($subject->exercise_3, true),
            'exercise4' => json_decode($subject->exercise_4, true),
            'studentAnswers' => json_decode($subject->student_answers, true),
            'results' => json_decode($subject->results, true)]);
    }

    public function prepare() {
        return view('exam/prepare');
    }

    public function scheduleExam(Request $request)
    {
        if ($request->ajax()) {
            $examInfo = $request->input('info');
            $examExercises = $request->input('exercises');
            $examInfo = json_decode($examInfo, true);
            $examExercises = json_decode($examExercises, true);
            $this->examBusiness->schedule($examInfo, $examExercises);
        }
    }

    public function showExams() {
        $exams = $this->examBusiness->getExams();
        return view('exam/program', ['exams' => $exams]);
    }
}
