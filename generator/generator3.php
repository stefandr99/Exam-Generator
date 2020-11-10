<?php

class Generator3 {
    private $enunt;
    private $letters;
    private $permutation;

    public function __construct() {
        $json = file_get_contents('php://input');
        $input = json_decode($json, TRUE);
        $this->enunt = $input["enunt"];

        $this->setLettersAndPermute();
        $this->applyPermutation();
        $this->applySort();
    }

    private function setLettersAndPermute() {
        $m = array();
        preg_match('/R\([*#$~_[\]A-Z,]*\)/', $this->enunt, $m);
        $expr = substr($m[0], 1);
        $this->letters = array();
        for($l = 0; $l < strlen($expr); $l++) {
            if(ctype_alpha($expr[$l]))
                array_push($this->letters, $expr[$l]);
        }
        $this->permutation = $this->letters;
        shuffle($this->permutation);
    }

    private function applyPermutation() {
        for($i = 0; $i < strlen($this->enunt); $i++) {
            if($this->enunt[$i] == '*' && $this->enunt[$i + 1] == '_') {
                $letter = $this->enunt[$i + 3];
                $this->enunt = substr_replace($this->enunt, $this->permutation[ord($letter) - 65], $i, 5);
            }
        }
        print_r($this->enunt);
    }

    private function applySort() {
        for($i = 0; $i < strlen($this->enunt); $i++) {
            if($this->enunt[$i] == '*' && $this->enunt[$i + 1] == '~') {
                $argumentArr = $this->extractArgumentTilda($i);
                $this->enunt = substr_replace($this->enunt, $argumentArr, $i, $this->getEnd($i + 2) - $i + 1);
            }
        }
        print_r($this->enunt);
    }

    private function extractArgumentTilda($index) {
        $index += 2; // ca sa ajung fix la indexul de dupa prima paranteza patrata
        $start = $index;
        $end = $this->getEnd($index);
        $argument = substr($this->enunt, $start, $end - $start + 1);
        $dependencies = array();
        $i = 0;
        while ($i < strlen($argument)) {
            if($argument[$i] == '*' && $argument[$i + 1] == '$') {
                $i += 3;
                $start2 = $i;
                while($argument[$i] != ']')
                    $i++;
                array_push($dependencies, substr($argument, $start2, $i - $start2));
            }
            $i++;
        }
        shuffle($dependencies);
        $sBuilder = $dependencies[0];
        for ($i = 1; $i < count($dependencies); $i++) {
            $sBuilder = $sBuilder . ", " . $dependencies[$i];
        }
        return $sBuilder;
    }

    private function getEnd($index) {
        $openPharantesis = 1;
        while ($openPharantesis) {
            $index++;
            if($this->enunt[$index] == '[')
                $openPharantesis++;
            if($this->enunt[$index] == ']')
                $openPharantesis--;
        }
        return $index;
    }

}
$g = new Generator3();


