<?php


class multimiFunctionale {
    private $letters; // literele exercitiului
    private $dependencies; // array de reguli
    private $leftDependenciesArray; // partea stanga a regulilor doar ca in format de array
    private $rightDependenciesArray; // partea dreapta a regulilor doar ca in format de array
    private $options; // optiunile exercitiului. Mereu vor fi 6
    private $solutions; // array de solutii pentru toate optiunile
    private $answer; // array-ul ce contine se va returna pentru optiuni. Va fi returnat de forma [[optiune], true/false]
    private $numberOfLetters; // numarul de litere din exercitiu (5/6)
    private $numberOfDependencies; // numarul de reguli (5/6)

    /**
     * multimiFunctionale constructor.
     * Se alege random daca vor fi 5 sau 6 litere. La fel si pentru numarul de reguli
     * Se apeleaza pe rand: createDependencies() - metoda care va crea regulile exercitiului
     *                      generateOptions() - se genereaza optiunile din care trebuie alese cele corecte.
     *                              Aici se apeleaza si functia euristica pentru 2 optiunni care vor fi mereu TRUE.
     *                      createSolutions() - se calculeaza solutia pentru fiecare optiune
     *                      correctsProblem() - corecteaza optiunile cu ajutorul array-ului de solutie si pune true/false
     */
    public function __construct() {
        $this->numberOfLetters = rand(5, 6);
        if($this->numberOfLetters == 5)
            $this->letters = array('A', 'B', 'C', 'D', 'E');
        else
            $this->letters = array('A', 'B', 'C', 'D', 'E', 'F');
        $this->solutions = array();
        $this->numberOfDependencies = rand(5, 6);
        $this->dependencies = array();
        $this->createDependencies();
        $this->generateOptions();
        $this->createSolutions();
        $this->correctsProblem();
    }

    /**
     * Metoda ce va crea regulile exercitiului
     * Bucla while scop: se genereaza o regula si nu se iese din bucla pana nu se genereaza una care nu a mai fost
     *      pana atunci in array-ul de reguli. Scopul e ca partea stanga a regulii sa fie unica.
     * Partea stanga a regulii: poate fi alcatuita din 1-3 litere. Se face o copie a array-ului de litere din care se scot
     *      literele care au aparut in stanga regulii. Nu vrem sa apara litere si in stanga si in dreapta unei reguli.
     *      Array-ul elementLeft - multimea de litere din care este alcatuita partea stanga a regulii. Multime ce va fi
     *      folosita pentru determinarea solutiilor.
     * Partea dreapta a regulii: poate fi alcatuita din (1, min(3,count(letterCopy))) deoarece este posibil ca numarul literelor
     *      ramase in letterCopy sa fie 2 (situatie: 5 litere in total, 3 litere in stanga, raman 2 litere pentru dreapta)
     *      Se aleg random literele care vor alcatui partea dreapta a regulii.
     *      Array-ul elementRight - multimea de litere din care este alcatuita partea dreapta a regulii. Aceasta va fi folosita
     *      pentru determinarea solutiei.
     */
    private function createDependencies() {
        for($i = 0; $i < $this->numberOfDependencies; $i++) {
            // alege elementul din stanga regulii
            $ok = false;
            $elementLeft = array();
            while(!$ok) {
                $n = rand(1, 3);
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

                if(!empty($this->dependencies != null)) {
                    if (!array_key_exists($element1, $this->dependencies))
                        $ok = true;
                }
                else $ok = true;
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

            $this->dependencies[$element1] = $element2;
            // Componentele de mai jos sunt regulile doar ca in forma de array in loc de string
            $this->leftDependenciesArray[$i] = $elementLeft;
            $this->rightDependenciesArray[$i] = $elementRight;
        }
    }

    /**
     * Generarea optiunilor exercitiului
     * Vor fi generate in pozitii random cu ajutorul euristicii 2 optiuni care sigur vor fi true.
     * Bucla while functioneaza pe acelasi principiu ca la generarea regulilor. Nu se doreste acelasi element in stanga de doua ori.
     * Vectorul solutie pentru fiecare optiune va fi mereu initializat cu literele care apar in stanga optiunii. La fel si vectorul
     *      'option2' care reprezinta multimea raspuns pentru fiecare optiune.
     * Se scoate din copia lettersCopy literele care au aparut deja in partea stanga a optiunii pentru a nu putea fi realse in dreapta
     *      optiunii.
     * Dreapta optiunii: se alege intre 0-2 litere pentru a fi adaugate in multimea de raspuns a fiecarei optiuni
     */
    private function generateOptions() {
        $r = range(0, 5);
        shuffle($r);
        $heuristics = array($r[0], $r[1]); // indecsii unde se vor pune optiunile euristice
        $heuristicIndex = 0;
        $heuristicOptions = $this->calculate();

        for($i = 0; $i < 6; $i++) {
            if(in_array($i, $heuristics)) {
                if(!empty($this->options))
                    while(array_key_exists($heuristicOptions[$heuristicIndex][0], $this->options))
                        $heuristicIndex++;
                $this->options[$heuristicOptions[$heuristicIndex][0]] = $heuristicOptions[$heuristicIndex][1];
                $this->solutions[$heuristicOptions[$heuristicIndex][0]] = array();
                for($l = 0; $l < strlen($heuristicOptions[$heuristicIndex][0]); $l++)
                    array_push($this->solutions[$heuristicOptions[$heuristicIndex][0]], $heuristicOptions[$heuristicIndex][0][$l]);
                $heuristicIndex++;
            }
            else {
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
                    if(!empty($this->options)) {
                        if (!array_key_exists($option1, $this->options))
                            $ok = true;
                    }
                    else $ok = true;

                }


                // adauga in elementul din dreapta optiunii (sau nu)
                $n = rand(0, 2);
                if ($n) {
                    $rand_keys = array_rand($lettersCopy, $n);
                    if ($n == 1)
                        array_push($option2, $lettersCopy[$rand_keys]);
                    else
                        array_push($option2, $lettersCopy[$rand_keys[0]], $lettersCopy[$rand_keys[1]]);
                }

                $this->sortOptions($option2);
                $this->options[$option1] = $option2;
            }
        }
    }

    /**
     * Se creeaza solutia pentru fiecare optiune. Initial, multimea de litere e alcatuita din literele elemetului din stanga optiunii.
     * Freq este un vector de frecventa pentru a se verifica daca s-a utilizat regula R(i).
     * Contidii bucla while: - daca nu s-a mai efectuat nicio modificare in multimea solutie pe parcursul iteratiei
     *                       - daca multimea solutiei este deja alcatuita din toate literele posibile
     * Se parcurge fiecare regula si daca elementul din stanga regulii ⊂ multimea solutie => se adauga in multimea solutie tot ce se afla
     *      in dreapta regulii. Se marcheaza regula si checkDone = false ca nu se poate iesi inca din calcul
     */
    private function createSolutions() {
        foreach(array_keys($this->solutions) as $key) {
            $solutionBuilder = $this->solutions[$key];
            $freq = array_fill(0, $this->numberOfDependencies, 0);
            $checkDone = false;
            while (!$checkDone && !$this->verifyFull($solutionBuilder)) {
                $checkDone = true;
                for($rule = 0; $rule < count($this->leftDependenciesArray); $rule++) {
                    if($freq[$rule] == 0) {
                        if(count(array_intersect($solutionBuilder, $this->leftDependenciesArray[$rule])) == count($this->leftDependenciesArray[$rule])) {
                            $this->addToSolution($solutionBuilder, $this->rightDependenciesArray[$rule]);
                            $freq[$rule] = 1;
                            $checkDone = false;
                        }
                    }
                }
            }
            sort($solutionBuilder);
            $this->solutions[$key] = $solutionBuilder;
        }
    }

    /**
     * Pentru fiecare optiune se verifica daca valoarea ei este egala cu solutia calculata mai sus
     * Se adauga in array-ul raspuns optiunea si raspunsul pentru aceasta: true/false
     */
    private function correctsProblem() {
        $result = array();
        foreach(array_keys($this->options) as $key) {
            if($this->options[$key] == $this->solutions[$key])
                array_push($result, array($key, implode($this->options[$key]), true));
            else
                array_push($result, array($key, implode($this->options[$key]), false));
        }
        $this->answer = $result;
    }


    /**
     * Se creeaza toate stringurile de 1, 2 litere (EX: A, B, C, AB, AC, BC)
     * @param $result - array-ul in care se vor pune stringurile
     */
    function createCombinations(&$result) {
        for($i = 0; $i < $this->numberOfLetters; $i++) {
            array_push($result, ($this->letters[$i]));
            for ($j = $i + 1; $j < $this->numberOfLetters; $j++) {
                array_push($result, ($this->letters[$i] . $this->letters[$j]));
            }
        }
    }

    /**
     * Are acelasi comportament ca metoda de calculare a solutiilor doar ca se contorizeaza numarul de intrari in
     *      structura if (cate reguli se folosesc)
     * @param $sol - array de multimi alcatuite din literele combinatiilor de mai sus. Ex: [['A'], ['A', 'B'], ['B', 'C'], etc.]
     * @return int - numarul de repetari realizate (sau numarul de reguli folosite)
     */
    private function heuristic(&$sol) {
        $solutionBuilder = $sol;
        $freq = array_fill(0, $this->numberOfDependencies, 0);
        $repeat = 0;
        $checkDone = false;
        while (!$checkDone && !$this->verifyFull($solutionBuilder)) {
            $checkDone = true;
            for($rule = 0; $rule < count($this->leftDependenciesArray); $rule++) {
                if($freq[$rule] == 0) {
                    if(count(array_intersect($solutionBuilder, $this->leftDependenciesArray[$rule])) == count($this->leftDependenciesArray[$rule])) {
                        $this->addToSolution($solutionBuilder, $this->rightDependenciesArray[$rule]);
                        $freq[$rule] = 1;
                        $repeat++;
                        $checkDone = false;
                    }
                }
            }
        }
        $sol = $solutionBuilder;
        return $repeat;
    }

    /**
     * Se calculeaza toate combinatiile de 1,2 litere din totalul de litere
     *      si pentru fiecare combinatie se calculeaza cati pasi se fac pentru a se determina solutia ei.
     * Se sorteaza vectorul descrescator dupa numarul de repetitii
     * Se aleg primele 2 optiuni cu cel mai mare numar de repetitii pentru descoperirea solutiei
     * Se vor alege 2 optiuni altfel: cea mai complexa optiune alcatuita din 2 litere si cea mai complexa alcatuita din 1 litera
     * @return array de forma array[optiune] = [solutie, repetitii]
     */
    public function calculate() {
        $result = array();
        $combinations = array();
        $this->createCombinations($combinations);
        foreach($combinations as $c) {
            $sol = array();
            for ($i = 0; $i < strlen($c); $i++) {
                array_push($sol, $c[$i]);
            }
            $reps = $this->heuristic($sol);
            $result[$c] = array($sol, $reps);
        }
        // sortarea vectorului euristic dupa numarul de repetari DESCENDENT
        $maxi = array_column($result, 1);
        array_multisort($maxi, SORT_DESC, $result);

        // returnez doar primele 2 solutii. Cele mai complicate (complexe) :)
        $resultCopy = $result;
        $result = array();
        $index = 0;
        $withTwoLetters = false;
        // prima optiune euristica va fi cu 2 litere, a doua neaparat cu 1 litera (sau invers)
        foreach(array_keys($resultCopy) as $key) {
            if(strlen($key) == 2 && $withTwoLetters == false) {
                sort($resultCopy[$key][0]);
                array_push($result, array($key, $resultCopy[$key][0]));
                $withTwoLetters = true;
                $index++;
            }
            if(strlen($key) == 1) {
                sort($resultCopy[$key][0]);
                array_push($result, array($key, $resultCopy[$key][0]));
                $index++;
            }
            if($index == 6) break;
        }
        return $result;
    }

    /**
     * @param $testSolution - multime de litere care reprezinta solutia partiala pentru o optiune
     * @return bool - true daca solutia partiala este alcatuita din toate literele posibile (caz in care terminam si de calculat solutia)
     *              - false daca solutia partiala nu este alcatuita din toate literele posibile
     */
    private function verifyFull($testSolution) {
        return count($testSolution) == $this->numberOfLetters;
    }

    /**
     * @param $solution - solutia partiala (curenta) la care se adauga alta multime
     * @param $toAdd - multime care se adauga la solutia curenta
     */
    private function addToSolution(&$solution, $toAdd) {
        $solution = array_unique(array_merge($solution, $toAdd));
    }

    /**
     * Sortarea multimii din dreapta optiunii
     * @param $options - multimea de litere care alcatuieste dreapta optiunii
     */
    private function sortOptions(&$options) {
        sort($options);
    }

    public function getRules() {
        return $this->dependencies;
    }

    public function getOptions() {
        return $this->options;
    }

    public function getSolutions() {
        return $this->solutions;
    }

    /**
     * Se returneaza JSON cu intreg exercitul
     */
    public function generate() {
        $i = 0;
        foreach(array_keys($this->dependencies) as $key) {
            $sigmaBuilder[$i] = array("leftside" => $key, "rightside" => $this->dependencies[$key]);
            $i++;
        }
        $sigma["count"] = $this->numberOfDependencies;
        $sigma["dependencies"] = (object)$sigmaBuilder;


        for($i = 0; $i < 6; $i++) {
            $optionBuilder[$i] = array("attr" => $this->answer[$i][0], "attr+" => $this->answer[$i][1],
                "answer" => $this->answer[$i][2]);
        }

        $result = array(
            "sigma" => $sigma,
            "options" => (object)$optionBuilder
        );
        return json_encode($result);
    }
}

$generator = new multimiFunctionale();
print_r($generator->generate());


