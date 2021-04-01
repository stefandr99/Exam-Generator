<?php

namespace App\Http\Controllers;

use App\Business\CourseBusiness;
use App\Business\ExamBusiness;
use App\Business\UserBusiness;
use App\Repository\Interfaces\ICourseRepository;
use App\Repository\Interfaces\IExamRepository;
use App\Repository\Interfaces\IUserRepository;
use DateInterval;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    private $userBusiness;
    private $courseBusiness;
    private $examBusiness;

    public function __construct(IUserRepository $userRepository, ICourseRepository $courseRepository,
                            IExamRepository $examRepository)
    {
        $this->middleware('auth');
        $this->userBusiness = new UserBusiness($userRepository);
        $this->courseBusiness = new CourseBusiness($courseRepository);
        $this->examBusiness = new ExamBusiness($examRepository);
    }

    public function showResult($examId, $userId) {
        $subjectInfo = $this->examBusiness->getExamResult($examId, $userId);

        $subjectInfo->exercises = json_decode($subjectInfo->exercises, true);
        $subjectInfo->student_answers = json_decode($subjectInfo->student_answers, true);
        $subjectInfo->results = json_decode($subjectInfo->results, true);

        return view('exam/result', [
            'info' => $subjectInfo
        ]);
    }

    public function prepare() {
        $courses = $this->courseBusiness->getAll();
        return view('exam/prepare', [
            'courses' => $courses
        ]);
    }

    public function scheduleExam(Request $request)
    {
        if ($request->ajax()) {
            $examInfo = $request->input('info');
            $examExercises = $request->input('exercises');
            $examPenalization = $request->input('penalization');

            $examInfo = json_decode($examInfo, true);
            $examExercises = json_decode($examExercises, true);
            $examPenalization = json_decode($examPenalization, true);

            $this->examBusiness->schedule($examInfo, $examExercises, $examPenalization);
        }
    }

    public function showExams() {
        $userId = Auth::id();
        $userRole = $this->userBusiness->getRoleById($userId);
        $yearAndSem = $this->userBusiness->getYearAndSemesterById($userId);

        $examsInformation = $this->examBusiness->getExams($userId, $userRole, $yearAndSem);
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
        $userName = $this->userBusiness->getNameById($userId);

        return view('exam/stealTheStart', ['name' => $userName->name]);
    }


    public function modifyExam($examId) {
        $exam = $this->examBusiness->getExamById($examId);
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

            $course = $this->courseBusiness->getIdByName($examInfo[0]);
            $this->examBusiness->updateExam($examId, $examInfo, $examExercises, $course);
        }
    }


}
