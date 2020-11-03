<?php

//$containsSearch = count(array_intersect($search_this, $all)) == count($search_this);

class multimiFunctionale {
    private $letters;
    private $rules;
    private $leftRulesArray; // partea stanga a regulilor doar ca in format de array
    private $rightRulesArray; // partea dreapta a regulilor doar ca in format de array
    private $options;
    private $leftOptions; // elementele din stanga optiunilor sub forma de array
    private $solutions;
    private $answer;
    private $numberOfLetters;
    private $numberOfRules;

    public function __construct() {
        $this->numberOfLetters = rand(5, 6);
        if($this->numberOfLetters == 5)
            $this->letters = array('A', 'B', 'C', 'D', 'E');
        else
            $this->letters = array('A', 'B', 'C', 'D', 'E', 'F');
        $this->solutions = array();
        $this->numberOfRules = rand(5, 6);
        $this->rules = array();
        $this->createRules();
        $this->generateOptions();
        $this->createSolutions();
    }

    private function createRules() {
        for($i = 0; $i < $this->numberOfRules; $i++) {
            $ok = false;
            $elementLeft = array();
            // alege elementul din stanga regulii
            while(!$ok) {
                // numarul de litere din elementul1 al regulii curente
                $n = rand(1, 3);
                // copie a vectorului de litere folosita pentru elementul 2 din dependenta din regula. Nu trebuie sa contina elementele din element1
                $lettersCopy = $this->letters;
                $rand_keys = array_rand($this->letters, $n);


                switch ($n) {
                    case 1:
                        $element1 = $this->letters[$rand_keys];
                        array_push($elementLeft, $this->letters[$rand_keys]);
                        \array_splice($lettersCopy, $rand_keys, 1);
                        break;
                    case 2:
                        $element1 = $this->letters[$rand_keys[0]] . $this->letters[$rand_keys[1]];
                        array_push($elementLeft, $this->letters[$rand_keys[0]], $this->letters[$rand_keys[1]]);
                        \array_splice($lettersCopy, $rand_keys[0], 1);
                        \array_splice($lettersCopy, $rand_keys[1] - 1, 1);
                        break;
                    case 3:
                        $element1 = $this->letters[$rand_keys[0]] . $this->letters[$rand_keys[1]] . $this->letters[$rand_keys[2]];
                        array_push($elementLeft, $this->letters[$rand_keys[0]], $this->letters[$rand_keys[1]],
                            $this->letters[$rand_keys[2]]);
                        \array_splice($lettersCopy, $rand_keys[0], 1);
                        \array_splice($lettersCopy, $rand_keys[1] - 1, 1);
                        \array_splice($lettersCopy, $rand_keys[2] - 2, 1);
                        break;
                }

                if (!array_key_exists($element1, $this->rules))
                    $ok = true;
            }

            // alage elementul din dreapta regulii
            $elementRight = array();
            if(count($lettersCopy) < 3)
                $n = rand(1, count($lettersCopy));
            else
                $n = rand(1, 3);
            $rand_keys = array_rand($lettersCopy, $n);
            $element2 = '';
            if($n == 1) {
                $element2 = $lettersCopy[$rand_keys];
                array_push($elementRight, $lettersCopy[$rand_keys]);
            }
            else
                for($j = 0; $j < $n; $j++) {
                    $element2 .= $lettersCopy[$rand_keys[$j]];
                    array_push($elementRight, $lettersCopy[$rand_keys[$j]]);
                }

            $this->rules[$element1] = $element2;
            // Componentele de mai jos sunt regulile doar ca in forma de array in loc de string
            $this->leftRulesArray[$i] = $elementLeft;
            $this->rightRulesArray[$i] = $elementRight;
        }
    }

    // Momentan doar optiuni triviale. Adica in dreapta optiunii se afla toate literele din stanga optiunii.
    // Trebuie sa regandesc cum sa generez optiunile
    private function generateOptions() {
        for($i = 0; $i < 6; $i++) {
            $ok = false;
            // alege elementul din stanga optiunii
            // se adauga in multimea elementului din dreapta toate literele elementului din stanga
            // ramane in bucla pana gaseste o optiune ce NU a fost folosita deja
            while (!$ok) {
                // numarul de litere din elementul1 al regulii curente
                $n = rand(1, 3);
                $lettersCopy = $this->letters;
                $rand_keys = array_rand($this->letters, $n);
                $option2 = array();
                switch ($n) {
                    case 1:
                        $option1 = $this->letters[$rand_keys];

                        // adaugam in multimea din dreapta a optiunii literele care apar in stanga optiunii
                        array_push($option2, $this->letters[$rand_keys]);

                        // adaugam si in solutie literele elementului din stanga
                        $this->solutions[$option1] = array($this->letters[$rand_keys]);

                        // scoatem din lista de litere literele pe care le-am folosit
                        \array_splice($lettersCopy, $rand_keys, 1);
                        break;
                    case 2:
                        $option1 = $this->letters[$rand_keys[0]] . $this->letters[$rand_keys[1]];

                        // adaugam in multimea din dreapta a optiunii literele care apar in stanga optiunii
                        array_push($option2, $this->letters[$rand_keys[0]], $this->letters[$rand_keys[1]]);

                        // adaugam si in solutie literele elementului din stanga
                        $this->solutions[$option1] = array($this->letters[$rand_keys[0]], $this->letters[$rand_keys[1]]);

                        // scoatem din lista de litere literele pe care le-am folosit
                        \array_splice($lettersCopy, $rand_keys[0], 1);
                        \array_splice($lettersCopy, $rand_keys[1] - 1, 1);
                        break;
                    case 3:
                        $option1 = $this->letters[$rand_keys[0]] . $this->letters[$rand_keys[1]] . $this->letters[$rand_keys[2]];

                        // adaugam in multimea din dreapta a optiunii literele care apar in stanga optiunii
                        array_push($option2, $this->letters[$rand_keys[0]], $this->letters[$rand_keys[1]],
                            $this->letters[$rand_keys[2]]);

                        // adaugam si in solutie literele elementului din stanga
                        $this->solutions[$option1] = array($this->letters[$rand_keys[0]], $this->letters[$rand_keys[1]],
                            $this->letters[$rand_keys[2]]);

                        // scoatem din lista de litere literele pe care le-am folosit
                        \array_splice($lettersCopy, $rand_keys[0], 1);
                        \array_splice($lettersCopy, $rand_keys[1] - 1, 1);
                        \array_splice($lettersCopy, $rand_keys[2] - 2, 1);
                        break;
                }

                if (!array_key_exists($option1, $this->rules))
                    $ok = true;
            }

            // adauga in elementul din dreapta optiunii (sau nu)
            $n = rand(0, 2);
            if($n) {
                $rand_keys = array_rand($lettersCopy, $n);
                if ($n == 1)
                    array_push($option2, $lettersCopy[$rand_keys]);
                else
                    array_push($option2, $lettersCopy[$rand_keys[0]], $lettersCopy[$rand_keys[1]]);
            }

            echo '<br>';
            $this->sortOptions($option2);
            $this->options[$option1] = $option2;
            $this->leftOptions[$i] = $option1;
        }
    }

    private function createSolutions() {
        foreach(array_keys($this->solutions) as $key) {
            $solutionBuilder = $this->solutions[$key];
            $freq = array_fill(0, $this->numberOfRules, 0);
            $checkDone = false;
            while (!$checkDone && !$this->verifyFull($solutionBuilder)) {
                $checkDone = true;
                for($rule = 0; $rule < count($this->leftRulesArray); $rule++) {
                    if($freq[$rule] == 0) {
                        if(count(array_intersect($solutionBuilder, $this->leftRulesArray[$rule])) == count($this->leftRulesArray[$rule])) {
                            $this->addToSolution($solutionBuilder, $this->rightRulesArray[$rule]);
                            $freq[$rule] = 1;
                            $checkDone = false;
                        }
                    }
                }
            }
            $this->solutions[$key] = $solutionBuilder;
        }
    }

    private function correctsProblem() {
        
    }

    private function verifyFull($testSolution) {
        return count($testSolution) == $this->numberOfLetters;
    }

    private function addToSolution(&$solution, $toAdd) {
        for($i = 0; $i < count($toAdd); $i++) {
            if(!in_array($toAdd[$i], $solution))
                array_push($solution, $toAdd[$i]);
        }
    }

    private function sortOptions(&$options) {
        sort($options);
    }

    public function getRules() {
        return $this->rules;
    }

    public function getOptions() {
        return $this->options;
    }

    public function getSolutions() {
        return $this->solutions;
    }
}

$ex2 = new multimiFunctionale();
print_r($ex2->getRules());
echo "<br>";
echo "<br>";
print_r($ex2->getOptions());
echo "<br>";
echo "<br>";
print_r($ex2->getSolutions());



/*
// Verificare cu site-ul vcosmin
//, array('B','E', 'F'), array('A','D','E')
//, array('A','D'), array('B')
$left = array(array('A','B'), array('A'), array('B','C'), array('B', 'D'));
$right = array(array('D'), array('B','D'), array('A'), array('C'));
$solution = array('AB' => array('A', 'B'), 'D' => array('D'), 'D' => array('D'),
    'BC' => array('B','C'),  'AD' => array('A','D'), 'CD' => array('C','D'));

foreach(array_keys($solution) as $key) {
    $solutionBuilder = $solution[$key];
    $freq = array_fill(0, 6, 0);
    $checkDone = false;
    while (!$checkDone && count($solutionBuilder) != 4) {
        $checkDone = true;
        for($rule = 0; $rule < count($left); $rule++) {
            if($freq[$rule] == 0) {
                if(count(array_intersect($solutionBuilder, $left[$rule])) == count($left[$rule])) {
                    addSolution($solutionBuilder, $right[$rule]);
                    $freq[$rule] = 1;
                    $checkDone = false;
                }
            }
        }
    }
    $solution[$key] = $solutionBuilder;
}
function addSolution(&$solution, $toAdd) {
    for($i = 0; $i < count($toAdd); $i++) {
        if(!in_array($toAdd[$i], $solution))
            array_push($solution, $toAdd[$i]);
    }
}

foreach (array_keys($solution) as $key) {
    sort($solution[$key]);
}

print_r($solution);
*/
