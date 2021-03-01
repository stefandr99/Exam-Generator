<?php


namespace App\Business;


use App\Courses;
use App\Exam;
use App\Subject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class ExamBusiness
{
    private $userBusiness;
    private $courseBusiness;

    public function __construct() {
        $this->userBusiness = new UserBusiness();
        $this->courseBusiness = new CourseBusiness();
    }

    private function generateDBType1() {
        $url = "http://localhost/bd/generator1.php";

        $c = curl_init($url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($c);
        curl_close($c);

        return $response;
    }

    private function generateDBType2() {
        $url = "http://localhost/bd/generator2.php";
        $json = file_get_contents("./resources/json_data/exercise2.json",0,null,null);

        $c = curl_init($url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, $json);
        curl_setopt($c, CURLOPT_HTTPHEADER, array('Content-Type: text/plain'));
        $response = curl_exec($c);
        curl_close($c);

        return $response;
    }

    private function generateDBType3() {
        $url = "http://localhost/bd/generator3.php";

        $c = curl_init($url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($c);
        curl_close($c);

        return $response;
    }

    private function generateDBType4() {
        $url = "http://localhost/bd/generator4.php";
        $json = file_get_contents("./resources/json_data/exercise4.json",0,null,null);

        $c = curl_init($url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, $json);
        curl_setopt($c, CURLOPT_HTTPHEADER, array('Content-Type: text/plain'));
        $response = curl_exec($c);
        curl_close($c);

        return $response;
    }

    public function generate($examId, $examInfo): array
    {
        $examInfo[0]->exercises_type = json_decode($examInfo[0]->exercises_type);

        switch ($examInfo[0]->course_name) {
            case 'Baze de date':
                $exercises = $this->generateDBSubject($examInfo[0]);
                break;
            case 'Proiectarea Algoritmilor':
                break;
        }

        $exercisesJson = json_encode($exercises);
        $userId = Auth::id();

        $subject = new Subject;
        $subject->user_id = $userId;
        $subject->exam_id = $examId;
        $subject->exercises = $exercisesJson;
        $subject->total_points = $examInfo[0]->total_points;
        $subject->save();

        return array($exercises, $examInfo[0]);
    }

    private function generateDBSubject($examInfo): array
    {
        $exercises = array();

        for($ex = 0; $ex < $examInfo->number_of_exercises; $ex++) {
            $exercises[$ex] = array();
            switch ($examInfo->exercises_type[$ex][0]) {
                case 'type-1':
                    $exercise = $this->generateDBType1();
                    $exercises[$ex]['exercise'] = json_decode($exercise, true);
                    $exercises[$ex]['points'] = $examInfo->exercises_type[$ex][1];
                    break;
                case 'type-2':
                    $exercise = $this->generateDBType2();
                    $exercises[$ex]['exercise'] = json_decode($exercise, true);
                    $exercises[$ex]['points'] = $examInfo->exercises_type[$ex][1];
                    break;
                case 'type-3':
                    $exercise = $this->generateDBType3();
                    $exercises[$ex]['exercise'] = json_decode($exercise, true);
                    $exercises[$ex]['points'] = $examInfo->exercises_type[$ex][1];
                    break;
                case 'type-4':
                    $exercise = $this->generateDBType4();
                    $exercises[$ex]['exercise'] = json_decode($exercise, true);
                    $exercises[$ex]['points'] = $examInfo->exercises_type[$ex][1];
                    break;
            }

        }
        return $exercises;
    }

    public function getExamInfo($examId): \Illuminate\Support\Collection
    {
        $result = DB::table('exams')
            ->join('courses', 'courses.id', '=', 'exams.course_id')
            ->select('courses.name as course_name', 'type', 'date', 'hours', 'minutes',
                'number_of_exercises', 'exercises_type', 'total_points')
            ->where('exams.id', $examId)
            ->get();

        return $result;
    }

    public function correct($studentAnswers, $exercisesNumber, $optionsNumber, $examId): array
    {
        $userId = Auth::id();
        $results = DB::table('subjects')
            ->select('exercises', 'total_points')
            ->where('user_id', $userId)
            ->where('exam_id', $examId)
            ->get();

        $exercises = json_decode($results[0]->exercises, true);

        $correctedExam = [];
        for ($currentExercise = 0; $currentExercise < $exercisesNumber; $currentExercise++) {
            $correctedExam[$currentExercise] = [];
            for ($i = 0; $i < $optionsNumber[$currentExercise]; $i++) {
                $correctedExam[$currentExercise][$i] = ($studentAnswers[$currentExercise][$i]
                    === $exercises[$currentExercise]['exercise']['options']['solution'][$i + 1]['answer']);
            }
        }
        $points = $this->getPoints($correctedExam);
        $correctedExam = json_encode($correctedExam);
        $studentAnswers = json_encode($studentAnswers);

        DB::table('subjects')
            ->where('user_id', $userId)
            ->where('exam_id', $examId)
            ->update(['obtained_points' => $points, 'student_answers' => $studentAnswers, 'results' => $correctedExam]);

        return array(0 => $examId, 1 => $userId);
    }

    private function getPoints($exam): int
    {
        $points = 0;
        foreach ($exam as $exercise) {
            $trues = count(array_filter($exercise));
            $falses = count($exercise) - $trues;
            $points += (3 - $falses > 0) ? (3 - $falses) : 0;
        }
        return $points;
    }

    public function getExamResult($examId, $userId) {
        $result = DB::table('subjects as s')
            ->join('exams as e', 'e.id', '=', 's.exam_id')
            ->join('courses as c', 'c.id', '=', 'e.course_id')
            ->select('c.name as course_name', 'e.type', 'e.date', 'e.number_of_exercises', 'e.total_points', 'e.minimum_points',
                        's.exercises', 's.obtained_points', 's.student_answers', 's.results')
            ->where('s.exam_id', $examId)
            ->where('s.user_id', $userId)
            ->get()
            ->first();

        return $result;
    }

    public function schedule($info, $exercises) {
        $courseId = $this->courseBusiness->getCourseId($info[0]);

        $exam = new Exam;
        $exam->course_id = $courseId->first()->id;
        $exam->type = $info[1];
        $exam->date = $info[2];
        $exam->hours = $info[3];
        $exam->minutes = $info[4];
        $exam->number_of_exercises = $exercises[0];
        $exam->exercises_type = json_encode($exercises[1]);
        $exam->total_points = $exercises[2];
        $exam->minimum_points = $info[5];
        $exam->save();
    }

    public function getExams(): array
    {
        $userId = Auth::id();
        $userRole = $this->userBusiness->getRole($userId);

        return ($userRole[0]->role == 2) ?
            $this->getExamsForTeacher($userId) :
            $this->getExamsForStudents($userId);
    }

    private function getExamsForTeacher($userId): array
    {

        $exams = DB::table('users')
            ->join('didactics', 'users.id', '=', 'didactics.teacher_id')
            ->join('courses', 'courses.id', '=', 'didactics.course_id')
            ->join('exams', 'courses.id', '=', 'exams.course_id')
            ->select('exams.id as exam_id', 'users.name as teacher_name', 'courses.name as course_name',
                'exams.type', 'exams.date', 'exams.hours', 'exams.minutes', 'exams.number_of_exercises',
                'exams.total_points', 'exams.minimum_points')
            ->where('users.id', $userId)
            ->where('exams.date', '>', now())
            ->orderBy('exams.date')
            ->get();

        $examsInformation = array(2, $exams);
        return $examsInformation;
    }

    private function getExamsForStudents($userId): array
    {
        $yearAndSemester = $this->userBusiness->getYearAndSemester($userId);

        $exams = DB::table('exams')
            ->join('courses', 'courses.id', '=', 'exams.course_id')
            ->select('exams.id as exam_id', 'courses.name as course_name', 'exams.type', 'exams.date',
                'exams.hours', 'exams.minutes', 'exams.number_of_exercises', 'exams.total_points', 'exams.minimum_points')
            ->where('courses.year', $yearAndSemester[0]->year)
            ->where('courses.semester', $yearAndSemester[0]->semester)
            ->where('exams.date', '>', now())
            ->orderBy('exams.date')
            ->get();

        $teachers = $this->getExamTeachers($exams);

        $examsInformation = array(3, $exams, $teachers);
        return $examsInformation;
    }

    public function getExamTeachers($exams): array
    {
        $teachers = array();
        foreach ($exams as $exam) {
            $teachers[$exam->exam_id] = DB::table('users')
                ->join('didactics', 'users.id', '=', 'didactics.teacher_id')
                ->join('courses', 'courses.id', '=', 'didactics.course_id')
                ->join('exams', 'courses.id', '=', 'exams.course_id')
                ->select('users.name')
                ->where('exams.id', $exam->exam_id)
                ->orderBy('users.created_at')
                ->get();
        }

        return $teachers;
    }

    public function getExamById($examId): \Illuminate\Support\Collection
    {
        $exam = DB::table('exams as e')
            ->join('courses as c', 'c.id', '=', 'e.course_id')
            ->select('c.name as course_name', 'e.*')
            ->where('e.id', $examId)
            ->get();

        return $exam;
    }

    public function updateExam($info, $exercises, $id) {
        $course = $this->courseBusiness->getCourseIdByName($info[0]);

        DB::table('exams')
            ->where('id', $id)
            ->update([
                'course_id' => $course[0]->id,
                'type' => $info[1],
                'date' => $info[2],
                'hours' => $info[3],
                'minutes' => $info[4],
                'number_of_exercises' => $exercises[0],
                'exercises_type' => json_encode($exercises[1]),
                'total_points' => $exercises[2],
                'minimum_points' => $info[5]
            ]);
    }
}
