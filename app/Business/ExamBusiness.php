<?php


namespace App\Business;


use App\Courses;
use App\Exam;
use App\Repository\Interfaces\IExamRepository;
use App\Subject;
use DateInterval;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class ExamBusiness
{
    private IExamRepository $examRepository;

    public function __construct(IExamRepository $examRepository) {
        $this->examRepository = $examRepository;
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
            default:
                $exercises = $this->generateDBSubject($examInfo[0]);
                break;
        }

        $exercisesJson = json_encode($exercises);
        $userId = Auth::id();

        $subject = array(
            'user_id' => $userId,
            'exam_id' => $examId,
            'exercises' => $exercisesJson,
            'total_points' => $examInfo[0]->total_points
        );
        $this->examRepository->createSubject($subject);

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
        return $this->examRepository->getInfoById($examId);
    }

    public function checkStealExamStart($examInfo) {
        $examDate = new DateTime($examInfo->starts_at);
        $presentDate = new DateTime("now", new DateTimeZone('UTC'));
        $presentDate->add(new DateInterval('PT2H'));
        return $presentDate < $examDate;
    }

    public function getExamTime($examInfo) {
        $examDate = new DateTime($examInfo->starts_at);
        $presentDate = new DateTime("now", new DateTimeZone('UTC'));
        $presentDate->add(new DateInterval('PT2H'));

        $examHours = intval($examDate->format('H')) + $examInfo->hours - intval($presentDate->format('H'));
        $examMinutes = intval($examDate->format('i')) + $examInfo->minutes - intval($presentDate->format('i'));
        $examSeconds = intval($examDate->format('s')) - intval($presentDate->format('s'));

        return (3600 * $examHours + 60 * $examMinutes + $examSeconds);
    }

    public function correct($studentAnswers, $exercisesNumber, $optionsNumber, $examId): array
    {
        $userId = Auth::id();
        $subjectExercises = $this->examRepository->getSubjectExercises($examId, $userId);

        $examInformation = $this->examRepository->getPenalizationInfoById($examId);

        $examInformation->penalization = json_decode($examInformation->penalization, true);

        $exercises = json_decode($subjectExercises[0]->exercises, true);

        $correctedExam = [];
        for ($currentExercise = 0; $currentExercise < $exercisesNumber; $currentExercise++) {
            $correctedExam[$currentExercise] = [];
            for ($i = 0; $i < $optionsNumber[$currentExercise]; $i++) {
                $correctedExam[$currentExercise][$i] = ($studentAnswers[$currentExercise][$i]
                    === $exercises[$currentExercise]['exercise']['options']['solution'][$i + 1]['answer']);
            }
        }

        $points = $this->getPoints($correctedExam, $exercises, $examInformation->penalization);

        $correctedExam = json_encode($correctedExam);
        $studentAnswers = json_encode($studentAnswers);

        $subjectToUpdate = array(
            'obtained_points' => $points,
            'student_answers' => $studentAnswers,
            'results' => $correctedExam
        );
        $this->examRepository->updateSubject($examId, $userId, $subjectToUpdate);

        return array(0 => $examId, 1 => $userId);
    }

    private function getPoints($exercisesResult,$subjectExercises, $penalization): int
    {
        $points = 0;
        if($penalization['type'] == 'points') {
            $counter = session('userPenalty');
            $points -= $this->calculatePointsPenalization($penalization, $counter);
        }
        $currExercise = 0;
        foreach ($exercisesResult as $exercise) {
            $trues = count(array_filter($exercise));
            $falses = count($exercise) - $trues;
            $currExercisePoints = $subjectExercises[$currExercise]['points'];
            $points += ($currExercisePoints - $falses > 0) ? ($currExercisePoints - $falses) : 0;
        }
        return $points;
    }

    private function calculatePenalization($examInformation) {
        $counter = session('userPenalty');
        switch ($examInformation['type']) {
            case 'points':
                return $this->calculatePointsPenalization($examInformation, $counter);
                break;
            case 'time':
                //return $this->calculateTimePenalization($examInformation, $counter);
                break;
            case 'limitations':
                //return $this->calculateLimitPenalization($examInformation, $counter);
                break;
            default:
                return 0;
        }
    }

    private function calculatePointsPenalization($examPenalizationInfo, $counter) {
        return $counter * $examPenalizationInfo['body']['points'];
    }

    public function getExamResult($examId, $userId) {
        return $this->examRepository->getResult($examId, $userId);
    }

    public function schedule($info, $exercises, $penalization) {
        $courseId = $this->courseBusiness->getIdByName($info[0]);
        $endTime = $this->getExamEndTime($info[2], $info[3], $info[4]);

        $exam = array(
            'id' => $courseId->id,
            'type' => $info[1],
            'starts_at' => $info[2],
            'ends_at' => $endTime,
            'hours' => $info[3],
            'minutes' => $info[4],
            'number_of_exercises' => $exercises[0],
            'exercises_type' => json_encode($exercises[1]),
            'total_points' => $exercises[2],
            'minimum_points' => $info[5],
            'penalization' => json_encode($penalization)
        );
        $this->examRepository->create($exam);
    }

    public function getExamEndTime($start, $hours, $minutes) {
        $endTime = new DateTime($start);
        $duration = 'PT' . strval($hours) . 'H' . strval($minutes) . 'M';
        $endTime->add(new DateInterval($duration));

        return $endTime;
    }

    public function getExams($userId, $userRole, $yearAndSem): array
    {
        if ($userRole->role == 2)
            return $this->getExamsForTeacher($userId);
        else {
            return $this->getExamsForStudents($userId, $yearAndSem);
        }
    }

    private function getExamsForTeacher($userId): array
    {
        $exams = $this->examRepository->getAllForTeachers($userId);

        $examsInformation = array(2, $exams);
        return $examsInformation;
    }

    private function getExamsForStudents($userId, $yearAndSemester): array
    {
        $exams = $this->examRepository->getAllForStudents($userId, $yearAndSemester->year, $yearAndSemester->semester);

        $teachers = $this->getExamTeachers($exams);

        $examsInformation = array(3, $exams, $teachers);
        return $examsInformation;
    }

    public function getExamTeachers($exams): array
    {
        $teachers = array();
        foreach ($exams as $exam) {
            $teachers[$exam->exam_id] = $this->examRepository->getTeachersByExam($exam->exam_id);
        }

        return $teachers;
    }

    public function getExamById($examId): \Illuminate\Support\Collection
    {
        return $this->examRepository->getExamById($examId);
    }

    public function updateExam($id, $info, $exercises, $course) {
        $endTime = $this->getExamEndTime($info[2], $info[3], $info[4]);

        $exam = array(
            'id' => $id,
            'course_id' => $course->id,
            'type' => $info[1],
            'starts_at' => $info[2],
            'ends_at' => $endTime,
            'hours' => $info[3],
            'minutes' => $info[4],
            'number_of_exercises' => $exercises[0],
            'exercises_type' => json_encode($exercises[1]),
            'total_points' => $exercises[2],
            'minimum_points' => $info[5]
        );

        $this->examRepository->update($exam);
    }
}
