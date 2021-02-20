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

    public function generate() {
        $exercises = $this->examBusiness->generate();
        $exercisesToView = array(
            json_decode($exercises[0], true),
            json_decode($exercises[1], true),
            json_decode($exercises[2], true),
            json_decode($exercises[3], true)
        );
        return view('exam/exam', ['exercises' => $exercisesToView]);
        //return view('exam', ['exercise1' => json_decode($exercises[0], true), 'exercise2' => json_decode($exercises[1], true),
        //    'exercise3' => json_decode($exercises[2], true), 'exercise4' => json_decode($exercises[3], true)]);
    }

    public function correctPartial(Request $request) {

        if($request->ajax()) {
            $studentAnswers = $request->input('arg');
            $studentAnswers = json_decode($studentAnswers);
            $userId = $this->examBusiness->correct($studentAnswers);
            return $userId;
        }
        else
            return 0;
    }

    public function showResult($id) {
        $subject = $this->examBusiness->getPartialResult($id);
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
            $examInfo = json_decode($examInfo);
            $examExercises = json_decode($examExercises);
            $this->examBusiness->schedule($examInfo, $examExercises);
        }
    }

    public function showExams() {
        $userId = Auth::id();
        $exams = $this->examBusiness->getExams();
        return view('exam/program', ['exams' => $exams]);
    }
}
