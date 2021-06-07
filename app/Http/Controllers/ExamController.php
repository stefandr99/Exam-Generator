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
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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

    public function prepareDB() {
        $dbCourse = $this->courseBusiness->getDatabasesId();
        $tomorrow = new DateTime("now", new DateTimeZone('Europe/Tiraspol'));
        $tomorrow->modify('+1 day');
        $tomorrow = $tomorrow->format('Y-m-d');
        return view('exam/prepareDB', [
            'dbCourse' => $dbCourse,
            'tomorrow' => $tomorrow
        ]);
    }

    public function prepareAny() {
        $courses = $this->courseBusiness->getAll();
        $tomorrow = new DateTime("now", new DateTimeZone('Europe/Tiraspol'));
        $tomorrow->modify('+1 day');
        $tomorrow = $tomorrow->format('Y-m-d');
        return view('exam/prepareAny', [
            'courses' => $courses,
            'tomorrow' => $tomorrow
        ]);
    }

    protected function DBExamValidator(array $data)
    {
        return Validator::make($data, [
            'exam_type' => ['required'],
            'exam_date' => ['date', 'after:today'],
            'exam_hours' => ['required', 'integer', 'between:0,48'],
            'exam_minutes' => ['required', 'integer', 'between:0,59'],
            'exam_exercise_0' => ['required'],
            'points_exercise_0' => ['required', 'integer'],
            'exam_minimum' => ['required', 'integer']
        ]);
    }

    public function scheduleDBExam(Request $request)
    {
        $this->DBExamValidator($request->all())->validate();
        $data = $request->all();

        //print_r($data);
        $this->examBusiness->scheduleDB($data);

        return redirect()->route('show_exams');
    }

    protected function anyExamValidator(array $data)
    {
        return Validator::make($data, [
            'exam_course' => ['required'],
            'exam_type' => ['required'],
            'exam_date' => ['date', 'after:today'],
            'exam_hours' => ['required', 'integer', 'between:0,48'],
            'exam_minutes' => ['required', 'integer', 'between:0,59'],
            'text_exercise_0' => ['required', 'string'],
            'exercise_0_option_0' => ['required', 'string'],
            'number_of_generated_options_0' => ['required', 'integer'],
            'correct_options_ex_0' => ['required', 'integer'],
            'wrong_options_ex_0' => ['required', 'integer'],
            'points_exercise_0' => ['required', 'integer'],
            'exam_minimum' => ['required', 'integer']
        ]);
    }

    public function scheduleAnyExam(Request $request)
    {
        $this->anyExamValidator($request->all())->validate();
        $data = $request->all();

        $this->examBusiness->scheduleAny($data);

        return redirect()->route('show_exams');
    }

    /*
    public function scheduleExam(Request $request)
    {
        if ($request->ajax()) {
            $examInfo = $request->input('info');
            $examExercises = $request->input('exercises');
            $examPenalization = $request->input('penalization');

            $examInfo = json_decode($examInfo, true);
            $examExercises = json_decode($examExercises, true);
            $examPenalization = json_decode($examPenalization, true);

            $this->examBusiness->scheduleDB($examInfo, $examExercises, $examPenalization);
        }
    }*/

    public function showExams() {
        $userId = Auth::id();
        $userRole = $this->userBusiness->getRoleById($userId);
        $yearAndSem = $this->userBusiness->getYearAndSemesterById($userId);

        $examsInformation = $this->examBusiness->getExams($userId, $userRole, $yearAndSem);
        $presentDate = new DateTime("now");
        $presentDate->add(new DateInterval('PT3H'));

        if($examsInformation[0] == 2)
            return view('program/teachersProgram', ['exams' => $examsInformation[1], 'presentDate' => $presentDate]);
        else
            return view('program/studentsProgram', ['exams' => $examsInformation[1], 'teachers' => $examsInformation[2],
                'presentDate' => $presentDate]);
    }

    public function showLast30DaysExams() {
        $userId = Auth::id();
        $yearAndSem = $this->userBusiness->getYearAndSemesterById($userId);
        $examsInformation = $this->examBusiness->getLast30DaysExams($userId, $yearAndSem);

        return view('program/teachersLast30DaysProgram', ['exams' => $examsInformation]);
    }

    public function stealStart($examId, $userId) {
        // de salvat in viitor in baza de date a fraudelor :))
        $userName = $this->userBusiness->getNameById($userId);
        $data = new DateTime("now");
        $data->add(new DateInterval("PT3H"));
        print_r($data);

        return view('exam/stealTheStart', ['name' => $userName->name]);
    }


    public function modifyDbExam($examId) {
        $dbCourse = $this->courseBusiness->getDatabasesId();
        $exam = $this->examBusiness->getExamById($examId);
        $exam->exercises = json_decode($exam->exercises, true);
        $exam->penalization = json_decode($exam->penalization, true);
        return view('exam/modify/database', [
            'exam' => $exam,
            'dbCourse' => $dbCourse
        ]);
    }

    public function modifyAnyExam($examId) {
        $exam = $this->examBusiness->getExamById($examId);
        $exam->exercises = json_decode($exam->exercises, true);
        $exam->penalization = json_decode($exam->penalization, true);
        return view('exam/modify/any', [
            'exam' => $exam
        ]);
    }

    public function updateExam(Request $request) {
        $data = $request->all();

        $this->examBusiness->update($data);

        return redirect()->route('show_exams');
    }

    public function showExamStats($id) {
        $examStatistics = $this->examBusiness->getExamStats($id);
        $neutralHour = DateTime::createFromFormat('H:i:s', '00:00:00')->format('H:i:s');

        return view('exam/showStatistics', [
            'exam' => $examStatistics['exam'],
            'subjects' => $examStatistics['subject'],
            'neutralHour' => $neutralHour,
            'filter' => 'none'
        ]);
    }

    public function filterExamStats(Request $request) {
        $examId = $request->exam;
        $filter = $request->filter;
        $examStatistics = $this->examBusiness->getFilteredExamStats($examId, $filter);
        $neutralHour = DateTime::createFromFormat('H:i:s', '00:00:00')->format('H:i:s');
        return view('exam/showStatistics', [
            'exam' => $examStatistics['exam'],
            'subjects' => $examStatistics['subject'],
            'neutralHour' => $neutralHour,
            'filter' => $filter
        ]);
    }

    public function promoteStudent($examId, $userId) {
        $this->examBusiness->promoteStudent($examId, $userId);

        return redirect()->route('show_exam_stats', $examId);
    }

    public function undoPromoteStudent($examId, $userId) {
        $this->examBusiness->undoPromoteStudent($examId, $userId);

        return redirect()->route('show_exam_stats', $examId);
    }

    public function searchSubject(Request $request) {
        $examId = $request->exam;
        $name = $request->name;

        $searchSubjectExamStatistics = $this->examBusiness->getFilteredExamStatsBySearch($examId, $name);
        $neutralHour = DateTime::createFromFormat('H:i:s', '00:00:00')->format('H:i:s');
        return view('exam/showStatistics', [
            'exam' => $searchSubjectExamStatistics['exam'],
            'subjects' => $searchSubjectExamStatistics['subject'],
            'neutralHour' => $neutralHour,
            'filter' => 'all'
        ]);
    }

    public function history() {
        $id = Auth::id();
        $exams = $this->examBusiness->history($id);

        return view('exam/student/history', [
            'exams' => $exams,
            'user_id' => $id
        ]);
    }
}
