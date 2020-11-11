<?php

class Generator3 {
    private $exercise;
    private $options;
    private $counter;
    private $letters;
    private $permutation;

    public function __construct() {
        $json = file_get_contents('php://input');
        $input = json_decode($json, TRUE);
        $this->exercise = $input["exercise"];
        $this->counter = $input["options"]["counter"];
        $this->options = array();
        for($i = 1; $i <= $this->counter; $i++)
            array_push($this->options, $input["options"][$i]);

        $this->setLettersAndPermute();
        $this->applyPermutation();
        $this->applySort();
    }

    private function setLettersAndPermute() {
        $m = array();
        preg_match('/R\([*#$~_[\]A-Z,]*\)/', $this->exercise, $m);
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
        for($i = 0; $i < strlen($this->exercise); $i++) {
            if($this->exercise[$i] == '*' && $this->exercise[$i + 1] == '_') {
                $letter = $this->exercise[$i + 3];
                $this->exercise = substr_replace($this->exercise, $this->permutation[ord($letter) - 65], $i, 5);
            }
        }
        for($op = 0; $op < count($this->options); $op++) {
            for($i = 0; $i < strlen($this->options[$op]); $i++) {
                if($this->options[$op][$i] == '*' && $this->options[$op][$i + 1] == '_') {
                    $letter = $this->options[$op][$i + 3];
                    $this->options[$op] = substr_replace($this->options[$op], $this->permutation[ord($letter) - 65], $i, 5);
                }
            }
        }
    }

    private function applySort() {
        for($i = 0; $i < strlen($this->exercise); $i++) {
            if($this->exercise[$i] == '*' && $this->exercise[$i + 1] == '~') {
                $argumentArr = $this->extractArgumentTilda($this->exercise, $i);
                $this->exercise = substr_replace($this->exercise, $argumentArr, $i, $this->getEnd($this->exercise, $i + 2) - $i + 1);
            }
             else if($this->exercise[$i] == '*' && $this->exercise[$i + 1] == '#') {
                $argumentArr = $this->extractArgumentNumberSign($this->exercise, $i);
                $this->exercise = substr_replace($this->exercise, $argumentArr, $i, $this->getEnd($this->exercise, $i + 2) - $i + 1);
            }
        }
        for($op = 0; $op < count($this->options); $op++) {
            for ($i = 0; $i < strlen($this->options[$op]); $i++) {
                if($this->options[$op][$i] == '*' && $this->options[$op][$i + 1] == '~') {
                    $argumentArr = $this->extractArgumentTilda($this->options[$op], $i);
                    $this->options[$op] = substr_replace($this->options[$op], $argumentArr, $i, $this->getEnd($this->options[$op], $i + 2) - $i + 1);
                }
                else if($this->options[$op][$i] == '*' && $this->options[$op][$i + 1] == '#') {
                    $argumentArr = $this->extractArgumentNumberSign($this->options[$op], $i);
                    $this->options[$op] = substr_replace($this->options[$op], $argumentArr, $i, $this->getEnd($this->options[$op], $i + 2) - $i + 1);
                }
            }
        }
        shuffle($this->options); // amestec optiunile
    }

    private function extractArgumentTilda($text, $index) {
        $index += 2; // ca sa ajung fix la indexul de dupa prima paranteza patrata
        $start = $index;
        $end = $this->getEnd($text, $index); // returneaza indexul unde se termina argumentul de sortat
        $argument = substr($text, $start, $end - $start + 1); // extrage argumentul de sortat
        // creez un array cu toate elementele din argument (de ex: A > B sau A > > BC etc.)
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
        shuffle($dependencies); // amestec dependentele
        $sBuilder = $dependencies[0]; // creez un string cu toate dependentele gasite ca argument
        for ($i = 1; $i < count($dependencies); $i++) {
            $sBuilder = $sBuilder . ", " . $dependencies[$i];
        }
        return $sBuilder;
    }

    private function extractArgumentNumberSign($text, $index) {
        $index += 2; // ca sa ajung fix la indexul de dupa prima paranteza patrata
        $start = $index;
        $end = $this->getEnd($text, $index); // returneaza indexul unde se termina argumentul de sortat
        $argument = substr($text, $start, $end - $start + 1); // extrage argumentul de sortat
        // creez un array cu toate elementele din argument (de ex: A > B sau A > > BC etc.)
        $orderedArg = array();
        $i = 0;
        while ($i < strlen($argument)) {
            if($argument[$i] == '*' && $argument[$i + 1] == '$') {
                $i += 3;
                $start2 = $i;
                while($argument[$i] != ']')
                    $i++;
                array_push($orderedArg, substr($argument, $start2, $i - $start2));
            }
            $i++;
        }
        sort($orderedArg); // ordonez literele
        $sBuilder = $orderedArg[0]; // creez un string cu toate dependentele gasite ca argument
        for ($i = 1; $i < count($orderedArg); $i++) {
            $sBuilder = $sBuilder . $orderedArg[$i];
        }
        return $sBuilder;
    }

    private function getEnd($text, $index) {
        $openPharantesis = 1;
        while ($openPharantesis) {
            $index++;
            if($text[$index] == '[')
                $openPharantesis++;
            if($text[$index] == ']')
                $openPharantesis--;
        }
        return $index;
    }

    public function generate() {
        $option["counter"] = $this->counter;
        for ($i = 1; $i <= $this->counter; $i++)
            $option[$i] = $this->options[$i - 1];

        $result = array(
            "exercise" => $this->exercise,
            "options" => $option
        );
        return json_encode($result);
    }

}
$g = new Generator3();
print_r($g->generate());


