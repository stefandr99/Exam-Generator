<?php


class dependencies {

    private $letters;
    private $matrix;
    private $solution;
    private $n;
    private $m;
    private $matrixCopy;
    private $solutionCopy;

    public function __construct() {
        $this->letters = array('A', 'B', 'C', 'D', 'E');

        $this->matrix = array(array('A', 'B', 'C', 'D', 'E'),
                        array(6, 2, 1, 3, 5),
                        array(9, 0, 5, 1, 9),
                        array(4, 2, 7, 3, 9),
                        array(4, 2, 1, 3, 5),
                        array(8, 0, 7, 3, 9),
                        array(8, 0, 1, 3, 5),
                        array(9, 0, 5, 1, 5),
                        array(6, 2, 7, 3, 9));

        $this->solution = array("*A  >  B",
                        "*D  >  >  CE",
                        "DC  >  E",
                        "D  >  CE",
                        "E  >  >  CB",
                        "*DCE  >  >  AB",
                        "*C  >  >  AB",
                        "D  >  DB",
                        "CB  >  E",
                        "*EA  >  >  DB");

        $this->matrixCopy = $this->matrix;
        $this->solutionCopy = $this->solution;
        $this->n = count($this->matrix);
        $this->m = count($this->letters);
    }

    private function getPermutation() {
        $permutation = $this->letters;
        shuffle($permutation);
        return $permutation;
    }

    private function moveColumns($permutation) {
        for($j = 0; $j < $this->m; $j++) {
            if($permutation[$j] != $this->matrixCopy[0][$j]) {
                // interschimba coloanele doar daca coloana permutata este diferita de cea originala
                $order = ord($permutation[$j]) - ord('A');
                // order este coloana permutata care corespunde coloanei curente j
                for($i = 1; $i < $this->n; $i++) {
                    $this->matrixCopy[$i][$j] = $this->matrix[$i][$order];
                }
            }
        }
    }

    private function moveLines() {
        $shuffledLines = range(1, $this->n - 1);
        shuffle($shuffledLines);
        $matrixCopy2 = $this->matrixCopy;

        for($i = 1; $i < $this->n; $i++) {
            if($shuffledLines[$i - 1] != $i) {
                // interschimba liniile doar daca linia permutata este diferita de cea originala
                for($j = 0; $j < $this->m; $j++) {
                    $matrixCopy2[$i][$j] = $this->matrixCopy[$shuffledLines[$i - 1]][$j];
                }
            }
        }
        $this->matrixCopy = $matrixCopy2;
    }

    /*
     * Pentru fiecare litera gasita in matricea de solutie
     *  o vom inlocui cu litera corespunzatoare din array-ul permutat
     * Ordinea unei litere in array-ul original de litere este dat de ASCII(letter) - ASCII('A'),
     *  de exemplu B are indexul 2 in vectorul original.
     * Fiecare litera din solutie, o vom inlocui cu indexul pe care ar trebui sa il aiba in array-ul original.
     */
    private function permuteSolution($permutation) {
        for($i = 0; $i < count($this->solution); $i++) {
            for($j = 0; $j < strlen($this->solution[$i]); $j++) {
                if(ord($this->solution[$i][$j]) >= 65 && ord($this->solution[$i][$j]) <= 90) {
                    $this->solutionCopy[$i][$j] = $permutation[ord($this->solution[$i][$j]) - 65];
                }
            }
        }
    }

    private function moveSolutionLines() {
        $shuffledLines = range(0, count($this->solution) - 1);
        shuffle($shuffledLines);

        for($i = 0; $i < count($this->solution); $i++) {
            $this->solutionCopy[$i] = $this->solution[$shuffledLines[$i]];
        }
    }

    /**
     * Generam o permutare a intervalului [0, 9]
     * Pentru fiecare cifra din matricea exercitiu, se va inlocui cu cifra care se afla
     *  pe acelasi index in vectorul permutat.
     * De exemplu daca numarul 6 (din vectorul permutat) se afla pe pozitia 9, atunci orice aparitie a lui 9
     *  din matricea exercitiu, va fi inlocuita cu cifra 6.
     */
    private function changeNumbers() {
        $numbers = range(0, 9);
        shuffle($numbers);
        for($i = 1; $i < $this->n; $i++) {
            for ($j = 0; $j < $this->m; $j++) {
                $this->matrixCopy[$i][$j] = $numbers[$this->matrixCopy[$i][$j]];
            }
        }
    }

    public function generate() {
        $permutation = $this->getPermutation();
        $this->moveColumns($permutation);
        $this->moveLines();
        $this->permuteSolution($permutation);
        $this->moveSolutionLines();
        $this->changeNumbers();

        $result = array(
            "matrix" => $this->matrixCopy,
            "solution" => $this->solutionCopy
        );
        return json_encode($result);
    }

}

$generator = new dependencies();
print_r($generator->generate());


