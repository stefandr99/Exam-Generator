<?php

namespace App\Http\Controllers;

use App\Business\Business;
use App\Business\ExamBusiness;
use App\Business\UserBusiness;
use DateInterval;
use DateTime;
use DateTimeZone;
use http\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ExamController extends Controller
{
    private $business;

    public function __construct()
    {
        $this->middleware('auth');
        $this->business = new Business();
    }

    public function generate($id) {
        $examInfo = $this->business->exam->getExamInfo($id);
        $userId = Auth::id();

        if($this->business->exam->checkStealExamStart($examInfo[0]))
            return redirect()->route('steal_start_exam', array('examId' => $id, 'userId' => $userId));

        $exercises = $this->business->exam->generate($id, $examInfo);
        $optionsNumber = array();
        for($index = 0; $index < count($exercises[0]); $index++) {
            $optionsNumber[$index] = $exercises[0][$index]['exercise']['options']['counter'];
        }

        $examTime = $this->business->exam->getExamTime($examInfo[0]);
        return view('exam/exam', ['exercises' => $exercises[0], 'info' => $examInfo[0],
            'optionsNumber' => $optionsNumber, 'examId' => $id,
            'examTime' => $examTime]);
    }

    public function correctPartial(Request $request) {

        if($request->ajax()) {
            $studentAnswers = $request->input('answers');
            $exercisesNumber = $request->input('exercisesNr');
            $optionsNumber = $request->input('optionsNr');
            $examId = $request->input('examId');
            $studentAnswers = json_decode($studentAnswers);
            $subjectInfo = $this->business->exam->correct($studentAnswers, $exercisesNumber, $optionsNumber, $examId);
            $subjectInfo = json_encode($subjectInfo);
            return $subjectInfo;
        }
        else
            return 0;
    }

    public function showResult($examId, $userId) {
        $subjectInfo = $this->business->exam->getExamResult($examId, $userId);

        $subjectInfo->exercises = json_decode($subjectInfo->exercises, true);
        $subjectInfo->student_answers = json_decode($subjectInfo->student_answers, true);
        $subjectInfo->results = json_decode($subjectInfo->results, true);

        return view('exam/result', [
            'info' => $subjectInfo
        ]);
    }

    public function prepare() {
        return view('exam/prepare');
        //return view('exam/timer');
    }

    public function scheduleExam(Request $request)
    {
        if ($request->ajax()) {
            $examInfo = $request->input('info');

            $examExercises = $request->input('exercises');
            $examInfo = json_decode($examInfo, true);
            $examExercises = json_decode($examExercises, true);
            $this->business->exam->schedule($examInfo, $examExercises);
        }
    }

    public function showExams() {
        $examsInformation = $this->business->exam->getExams();
        $presentDate = new DateTime("now");
        $presentDate->add(new DateInterval('PT2H'));

        if($examsInformation[0] == 2)
            return view('program/TeachersProgram', ['exams' => $examsInformation[1], 'presentDate' => $presentDate]);
        else
            return view('program/studentsProgram', ['exams' => $examsInformation[1], 'teachers' => $examsInformation[2],
                'presentDate' => $presentDate]);
    }

    public function stealStart($examId, $userId) {
        // de salvat in viitor in baza de date a fraudelor :))
        $userName = $this->business->user->getName($userId);

        return view('exam/stealTheStart', ['name' => $userName->name]);
    }


    public function modifyExam($examId) {
        $exam = $this->business->exam->getExamById($examId);
        $exam[0]->exercises_type = json_decode($exam[0]->exercises_type, true);
        return view('exam/modify', ['exam' => $exam[0]]);
    }

    public function updateExam(Request $request) {
        if ($request->ajax()) {
            $examInfo = $request->input('info');
            $examExercises = $request->input('exercises');
            $examId = $request->input('id');
            $examInfo = json_decode($examInfo, true);
            $examExercises = json_decode($examExercises, true);
            $this->business->exam->updateExam($examInfo, $examExercises, $examId);
        }
    }
}
