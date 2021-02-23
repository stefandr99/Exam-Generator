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
            $subjectInfo = $this->examBusiness->correct($studentAnswers, $exercisesNumber, $optionsNumber, $examId);
            return $subjectInfo;
        }
        else
            return 0;
    }

    public function showResult($examId, $userId) {
        $subjectInfo = $this->examBusiness->getExamResult($examId, $userId);

        $subjectInfo->exercises = json_decode($subjectInfo->exercises, true);
        $subjectInfo->student_answers = json_decode($subjectInfo->student_answers, true);
        $subjectInfo->results = json_decode($subjectInfo->results, true);

        return view('exam/result', [
            /*'courseName' => $subjectInfo->name,
            'exercises' => json_decode($subjectInfo->exercises, true),
            'examType' => $subjectInfo->type,
            'examDate' => $subjectInfo->date,
            'NumberOfExercises' => $subjectInfo->number_of_exercises,
            'totalPoints' => $subjectInfo->total_points,
            'minimumPoints' => $subjectInfo->minimum_points,
            'obtainedPoints' => $subjectInfo->obtained_points,
            'studentAnswers' => json_decode($subjectInfo->student_answers, true),
            'results' => json_decode($subjectInfo->results, true)*/
            'info' => $subjectInfo
        ]);
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
