<?php


namespace App\Business;


class ExamBusiness
{
    public function generateFirst() {
        $url = "http://localhost/bd/dependente1.php";

        $c = curl_init($url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($c);
        curl_close($c);

        return json_decode($response, true);
    }

    public function generateSecond() {
        $url = "http://localhost/bd/generator4.php";
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
        $url = "http://localhost/bd/dependente2.php";

        $c = curl_init($url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($c);
        curl_close($c);

        return json_decode($response, true);
    }

    public function generateFourth() {
        $url = "http://localhost/bd/generator3.php";
        $json = file_get_contents("./resources/json_data/exercise4.json",0,null,null);

        $c = curl_init($url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, $json);
        curl_setopt($c, CURLOPT_HTTPHEADER, array('Content-Type: text/plain'));
        $response = curl_exec($c);
        curl_close($c);

        return json_decode($response, true);
    }
}
