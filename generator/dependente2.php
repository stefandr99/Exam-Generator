<?php

$input = array("Neo", "Morpheus", "Trinity", "Cypher", "Tank");
$rand_keys = array_rand($input, 2);
echo $rand_keys[0] . "\n";
echo $rand_keys[1] . "\n";

class multimiFunctionale {
    private $letters;
    private $factors;
    private $rules;
    private $options;
    private $numberOfLetters;
    private $numberOfRules;

    public function __construct() {
        $this->numberOfLetters = rand(5, 6);
        if($this->numberOfLetters == 5)
            $this->letters = array('A', 'B', 'C', 'D', 'E');
        else
            $this->letters = array('A', 'B', 'C', 'D', 'E', 'F');

        $this->numberOfRules = rand(4, 6);
        $this->createRules();
    }

    private function createRules() {
        for($i = 0; $i < $this->numberOfRules; $i++) {
            $ok = false;
            while(!$ok) {
                // numarul de litere din elementul1 al regulii curente
                $n = rand(1, 2);
                $rand_keys = array_rand($this->letters, $n);
                $element1 = $this->letters[$rand_keys[0]];
                // copie a vectorului de litere folosita pentru elementul 2 din dependenta din regula. Nu trebuie sa contina elementele din element1
                $lettersCopy = $this->letters;
                \array_splice($lettersCopy, $rand_keys[0], 1);
                if($n == 2) {
                    $element1 .= $this->letters[$rand_keys[1]];
                    // daca s-a ales o regula cu 2 litere pe element1, se scoate si a doua litera din vectorul copie
                    \array_splice($lettersCopy, $rand_keys[1], 1);
                }
                if (!array_key_exists($element1, $this->rules))
                    $ok = true;
            }

            $n = rand(1, 3);
        }
    }
}
