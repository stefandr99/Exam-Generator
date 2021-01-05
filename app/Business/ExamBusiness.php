<?php


namespace App\Business;


use App\Result;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ExamBusiness
{
    private $exercises;
    private $numberOfExercises = 4;

    public function generateFirst() {
        $url = "http://localhost/bd/generator1.php";

        $c = curl_init($url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($c);
        curl_close($c);

        return json_decode($response, true);
    }

    public function generateSecond() {
        $url = "http://localhost/bd/generator2.php";
        $json = file_get_contents("./resources/json_data/exercise2.json",0,null,null);

        $c = curl_init($url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, $json);
        curl_setopt($c, CURLOPT_HTTPHEADER, array('Content-Type: text/plain'));
        $response = curl_exec($c);
        curl_close($c);

        return json_decode($response, true);
    }

    public function generateThird() {
        $url = "http://localhost/bd/generator3.php";

        $c = curl_init($url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($c);
        curl_close($c);

        return json_decode($response, true);
    }

    public function generateFourth() {
        $url = "http://localhost/bd/generator4.php";
        $json = file_get_contents("./resources/json_data/exercise4.json",0,null,null);

        $c = curl_init($url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, $json);
        curl_setopt($c, CURLOPT_HTTPHEADER, array('Content-Type: text/plain'));
        $response = curl_exec($c);
        curl_close($c);

        return json_decode($response, true);
    }

    public function correct($answers) {
        $this->exercises = Session::get('exercises');
        $correctedPartial = [];
        for ($currentExercise = 1; $currentExercise <= $this->numberOfExercises; $currentExercise++) {
            $correctedPartial[$currentExercise] = [];
            for ($i = 1; $i <= $this->exercises[$currentExercise - 1]['options']['counter']; $i++) {
                $correctedPartial[$currentExercise][$i] =
                    ($answers[$currentExercise][$i] === $this->exercises[$currentExercise - 1]['options']['solution'][$i]['answer']);
            }
        }
        $points = $this->getPoints($correctedPartial);
        $partial = array('correctedPartial' => $correctedPartial, 'points' => $points);
        $userId = Auth::id();
        $result = new Result;
        $result->user_id = $userId;
        $result->points = $points;
        $result->save();

        return $partial;
    }

    private function getPoints($partial) {
        $points = 0;
        foreach ($partial as $exercise) {
            $trues = count(array_filter($exercise));
            $falses = count($exercise) - $trues;
            $points += (3 - $falses > 0) ? (3 - $falses) : 0;
        }
        return $points;
    }
}
