<?php


namespace App\Business;


use App\Repository\Interfaces\ISubjectRepository;
use App\Timing;
use DateInterval;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Auth;

class SubjectBusiness
{
    private $subjectRepository;

    public function __construct(ISubjectRepository $subjectRepository) {
        $this->subjectRepository = $subjectRepository;
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

    public function generate($examId, $examInfo)
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
        $this->subjectRepository->createSubject($subject);

        return array($exercises, $examInfo[0]);
    }

    private function generateDBSubject($examInfo)
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

    public function markExamTiming($examId, $forced) {
        $userId = Auth::id();

        $submitDate = new DateTime("now", new DateTimeZone('Europe/Tiraspol'));
        $timeInfo = array(
            'userId' => $userId,
            'examId' => $examId,
            'submitDate' => $submitDate,
            'forced' => $forced
        );

        $this->subjectRepository->markExamTiming($timeInfo);
    }

    public function correct($studentAnswers, $exercisesNumber, $optionsNumber, $examId)
    {
        $userId = Auth::id();

        $subjectExercises = $this->subjectRepository->getSubjectExercises($examId, $userId);
        $examInformation = $this->subjectRepository->getPenalizationInfoById($examId);
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

        $subjectWithAnswers = array(
            'obtained_points' => $points,
            'student_answers' => $studentAnswers,
            'results' => $correctedExam
        );
        $this->subjectRepository->updateSubject($examId, $userId, $subjectWithAnswers);

        return array(0 => $examId, 1 => $userId);
    }

    private function getPoints($exercisesResult,$subjectExercises, $penalization)
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
        return 0;
    }

    private function calculatePointsPenalization($examPenalizationInfo, $counter) {
        return $counter * $examPenalizationInfo['body']['points'];
    }
}
