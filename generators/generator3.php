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
            array_push($this->options, array($input["options"][$i]["option"], $input["options"][$i]["answer"]));

        $this->setLettersAndPermute();
        $this->applyPermutation();
        $this->applySort();
    }

    /**
     * Cautam in enunt expresia care face match cu pattern-ul respectiv. De fapt cautam ce este intre parantezele relatiei (literele)
     * Sarim peste "R" si incepem cautarea, atunci cand gasim o litera, o adaugam in array
     */
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

    /**
     * Parcurgem enuntul si textul fiecarei optiuni.
     * Atunci cand intalnim caracterele "*_" inseamna ca urmeaza o litera ce trebuie inlocuita cu litera corespunzatoare permutarii
     * Fiind in formatul *_[A], litera se obtine la indexul i + 3, i fiind indexul unde se afla "*"
     * Se inlocuieste intreaga structura "*_[A]" (lungime = 5) cu litera corespunzatoare in permutare
     */
    private function applyPermutation() {
        for($i = 0; $i < strlen($this->exercise); $i++) {
            if($this->exercise[$i] == '*' && $this->exercise[$i + 1] == '_') {
                $letter = $this->exercise[$i + 3];
                $this->exercise = substr_replace($this->exercise, $this->permutation[ord($letter) - 65], $i, 5);
            }
        }
        for($op = 0; $op < count($this->options); $op++) {
            for($i = 0; $i < strlen($this->options[$op][0]); $i++) {
                if($this->options[$op][0][$i] == '*' && $this->options[$op][0][$i + 1] == '_') {
                    $letter = $this->options[$op][0][$i + 3];
                    $this->options[$op][0] = substr_replace($this->options[$op][0], $this->permutation[ord($letter) - 65], $i, 5);
                }
            }
        }
    }

    /**
     * Parcurgem enuntul si textul fiecarei optiuni.
     * Atunci cand se intalnesc caracterele "*~" SAU "*#" se realizeaza ordonarea random, respectiv alfabetica
     * Se apeleaza metoda ... care va returna un string reprezentand elementele extrase si sortate din interiorul *~[...] sau *#[...]
     * Se determina pozitiile de start si final ale substringului care va fi inlocuit cu stringul extras si ordonat primit de la metoda ...
     */
    private function applySort() {
        for($i = 0; $i < strlen($this->exercise); $i++) {
            if($this->exercise[$i] == '*' && $this->exercise[$i + 1] == '~') {
                $sortedArgument = $this->extractAndRandomArguments($this->exercise, $i);
                $start = $i;
                $end = $this->getEnd($this->exercise, $i + 2);
                $this->exercise = substr_replace($this->exercise, $sortedArgument, $start, $end - $start + 1);
            }
            else if($this->exercise[$i] == '*' && $this->exercise[$i + 1] == '#') {
                $sortedArgument = $this->extractAndSortArguments($this->exercise, $i);
                $start = $i;
                $end = $this->getEnd($this->exercise, $i + 2);
                $this->exercise = substr_replace($this->exercise, $sortedArgument, $start, $end - $start + 1);
            }
        }
        for($op = 0; $op < count($this->options); $op++) {
            for ($i = 0; $i < strlen($this->options[$op][0]); $i++) {
                if($this->options[$op][0][$i] == '*' && $this->options[$op][0][$i + 1] == '~') {
                    $sortedArgument = $this->extractAndRandomArguments($this->options[$op][0], $i);
                    $start = $i;
                    $end = $this->getEnd($this->options[$op][0], $i + 2);
                    $this->options[$op][0] = substr_replace($this->options[$op][0], $sortedArgument, $start, $end - $start + 1);
                }
                else if($this->options[$op][0][$i] == '*' && $this->options[$op][0][$i + 1] == '#') {
                    $sortedArgument = $this->extractAndSortArguments($this->options[$op][0], $i);
                    $start = $i;
                    $end = $this->getEnd($this->options[$op][0], $i + 2);
                    $this->options[$op][0] = substr_replace($this->options[$op][0], $sortedArgument, $start, $end - $start + 1);
                }
            }
        }
        shuffle($this->options); // amestec optiunile
    }

    /**
     * Metoda ce va extrage argumentele din interiorul [..] si le va sorta random.
     * Se porneste de la index + 2 pentru a se extrage exact structura "[...]", fara "*~".
     * Se obtine indexul unde se termina argumentul, apoi se extrage acesta pentru a se fi sortat random
     * Se parcurge intreg argumentul si se adauga fiecare element in array-ul care va fi sortat
     * Se face shuffle pe array-ul de elemente din argument, apoi se construieste un string ce va fi pus in enunt/optiune
     * @param $text - textul in care se cauta argumentele de sortat
     * @param $index - indexul de unde incep argumentele in textul primit
     * @return mixed|string - argumentele extrase si sortate random sub forma de string
     */
    private function extractAndRandomArguments($text, $index) {
        $index += 2;
        $start = $index;
        $end = $this->getEnd($text, $index);
        $argument = substr($text, $start, $end - $start + 1);
        $argumentsArray = array();
        $i = 0;
        while ($i < strlen($argument)) {
            if($argument[$i] == '*' && $argument[$i + 1] == '$') {
                $i += 3;
                $startArgument = $i;
                while($argument[$i] != ']')
                    $i++;
                $endArgument = $i;
                array_push($argumentsArray, substr($argument, $startArgument, $endArgument - $startArgument));
            }
            $i++;
        }
        shuffle($argumentsArray);
        $sBuilder = $argumentsArray[0];
        for ($i = 1; $i < count($argumentsArray); $i++) {
            $sBuilder = $sBuilder . ", " . $argumentsArray[$i];
        }
        return $sBuilder;
    }

    /**
     * Metoda ce va extrage argumentele din interiorul [..] si le va sorta alfabetic.
     * Se porneste de la index + 2 pentru a se extrage exact structura "[...]", fara "#~".
     * Se obtine indexul unde se termina argumentul, apoi se extrage acesta pentru a se fi sortat alfabetic
     * Se parcurge intreg argumentul si se adauga fiecare element in array-ul care va fi sortat
     * Se face sort pe array-ul de elemente din argument, apoi se construieste un string ce va fi pus in enunt/optiune
     * @param $text - textul in care se cauta argumentele de sortat
     * @param $index - indexul de unde incep argumentele in textul primit
     * @return mixed|string - argumentele extrase si sortate random sub forma de string
     */
    private function extractAndSortArguments($text, $index) {
        $index += 2;
        $start = $index;
        $end = $this->getEnd($text, $index);
        $argument = substr($text, $start, $end - $start + 1);
        $argumentsArray = array();
        $i = 0;
        while ($i < strlen($argument)) {
            if($argument[$i] == '*' && $argument[$i + 1] == '$') {
                $i += 3;
                $startArgument = $i;
                while($argument[$i] != ']')
                    $i++;
                $endArgument = $i;
                array_push($argumentsArray, substr($argument, $startArgument, $endArgument - $startArgument));
            }
            $i++;
        }
        sort($argumentsArray);
        $sBuilder = $argumentsArray[0];
        for ($i = 1; $i < count($argumentsArray); $i++) {
            $sBuilder = $sBuilder . $argumentsArray[$i];
        }
        return $sBuilder;
    }

    /**
     * Metoda ce va determina finalul unui argument care va trebui sa fie extras si sortat
     * Se realizeaza dupa numarul de paranteze deschise. Se pleaca de la prima paranteza deschisa "*~["/"*#["
     * Se itereaza prin text pana cand se ajunge ca numarul de paranteze deschise sa fie = 0. In acel moment, s-a terminat argumentul cautat
     * @param $text - textul in care se cauta finalul argumentului curent
     * @param $index - indexul unde incepe argumentul care va fi extras si sortat random/alfabetic
     * @return int - indexul unde se termina argumentul care va fi extras si sortat random/alfabetic
     */
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

    /**
     * Se creeaza json-ul reprezentand exercitiul
     * @return false|string - json-ul reprezentand exercitiul
     */
    public function generate() {
        $option["counter"] = $this->counter;
        for ($i = 1; $i <= $this->counter; $i++)
            $option[$i] = array("option" => $this->options[$i - 1][0], "answer" => $this->options[$i - 1][1]);

        $result = array(
            "exercise" => $this->exercise,
            "options" => $option
        );
        return json_encode($result);
    }

}
$g = new Generator3();
print_r($g->generate());


