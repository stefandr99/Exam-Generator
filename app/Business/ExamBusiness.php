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
    private $numberOfExercises = 4;

    public function __construct() {
        $this->userBusiness = new UserBusiness();
    }

    private function generateFirst() {
        $url = "http://localhost/bd/generator1.php";

        $c = curl_init($url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($c);
        curl_close($c);

        return $response;
    }

    private function generateSecond() {
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

    private function generateThird() {
        $url = "http://localhost/bd/generator3.php";

        $c = curl_init($url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($c);
        curl_close($c);

        return $response;
    }

    private function generateFourth() {
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

    public function generate() {
        $exercise1 = $this->generateFirst();
        $exercise2 = $this->generateSecond();
        $exercise3 = $this->generateThird();
        $exercise4 = $this->generateFourth();
        $exercises = array(
            'exercise1' => $exercise1,
            'exercise2' => $exercise2,
            'exercise3' => $exercise3,
            'exercise4' => $exercise4
        );
        $userId = Auth::id();
        Subject::create([
            'user_id' => $userId,
            'exam_id' => 1, // de refacut la momentul potrivit
            'exercises' => $exercises, // la fel
            'total_points' => 12 // la fel
        ]);

        $result = array($exercise1, $exercise2, $exercise3, $exercise4);
        return $result;
    }

    public function correct($studentAnswers): int
    {
        $userId = Auth::id();
        $results = DB::table('subjects')
            ->select('exercise_1', 'exercise_2', 'exercise_3', 'exercise_4')
            ->where('user_id', $userId)
            ->get()
            ->first();
        $this->exercises = array(json_decode($results->exercise_1, true), json_decode($results->exercise_2, true),
                                    json_decode($results->exercise_3, true), json_decode($results->exercise_4, true));
        $correctedPartial = [];
        for ($currentExercise = 1; $currentExercise <= $this->numberOfExercises; $currentExercise++) {
            $correctedPartial[$currentExercise] = [];
            for ($i = 1; $i <= $this->exercises[$currentExercise - 1]['options']['counter']; $i++) {
                $correctedPartial[$currentExercise][$i] =
                    ($studentAnswers[$currentExercise][$i] === $this->exercises[$currentExercise - 1]['options']['solution'][$i]['answer']);
            }
        }
        $points = $this->getPoints($correctedPartial);
        $correctedPartial = json_encode($correctedPartial);
        $studentAnswers = json_encode($studentAnswers);

        DB::table('subjects')
            ->where('user_id', $userId)
            ->update(['points' => $points, 'student_answers' => $studentAnswers, 'results' => $correctedPartial]);

        return $userId;
    }

    private function getPoints($partial): int
    {
        $points = 0;
        foreach ($partial as $exercise) {
            $trues = count(array_filter($exercise));
            $falses = count($exercise) - $trues;
            $points += (3 - $falses > 0) ? (3 - $falses) : 0;
        }
        return $points;
    }

    public function getPartialResult($userId) {
        $result = DB::table('subjects')
            ->where('user_id', $userId)
            ->get()
            ->first();

        return $result;
    }

    public function schedule($info, $exercises) {
        $courseId = $this->getCourseId($info[0]);
        return Exam::create([
            'course_id' => $courseId->first()->id,
            'type' => $info[1],
            'date' => $info[2],
            'hours' => $info[3],
            'minutes' => $info[4],
            'number_of_exercises' => $exercises[0],
            'exercises_type' => json_encode($exercises[1]),
            'total_points' => $exercises[2]
        ]);
    }

    private function getCourseId($course) {
        $result = DB::table('courses')
            ->select('id')
            ->where('name', $course)
            ->get();

        return $result;
    }

    public function getExams() {
        $userId = Auth::id();
        $userRole = $this->userBusiness->getRole($userId);

        if($userRole[0]->role == 2) {
            $exams = DB::table('users')
                ->join('didactics', 'users.id', '=', 'didactics.teacher_id')
                ->join('courses', 'courses.id', '=', 'didactics.course_id')
                ->join('exams', 'courses.id', '=', 'exams.course_id')
                ->select('users.name as teacher_name', 'courses.name as course_name', 'exams.type', 'exams.date',
                    'exams.hours', 'exams.minutes', 'exams.number_of_exercises', 'exams.total_points')
                ->where('users.id', $userId)
                ->get();
            return $exams;
        }
        elseif ($userRole[0]->role == 3) {
            $yearAndSemester = $this->userBusiness->getYearAndSemester($userId);

            $exams = DB::table('users')
                ->join('didactics', 'users.id', '=', 'didactics.teacher_id')
                ->join('courses', 'courses.id', '=', 'didactics.course_id')
                ->join('exams', 'courses.id', '=', 'exams.course_id')
                ->select('users.name as teacher_name', 'courses.name as course_name', 'exams.type', 'exams.date',
                    'exams.hours', 'exams.minutes', 'exams.number_of_exercises', 'exams.total_points')
                ->where('courses.year', $yearAndSemester[0]->year)
                ->where('courses.semester', $yearAndSemester[0]->semester)
                ->get();

            return $exams;
        }
        return null;
    }
}
