<?php


class Generator4 {
    private $problemText1;
    private $problemText2;
    private $sentences;
    private $sentencesCounter; // numarul de fraze din json
    private $numberOfSentences; // nnumarul fraze alese pentru exercitiu
    private $sentencesChosen;
    private $options;
    private $optionsCounter; // numarul de optiuni primite in json
    private $numberOfOptions; // numarul de optiuni alese pentru exercitiu
    private $optionsChosen;
    private $correctCounter;
    private $numberOfCorrect;
    private $correct;
    private $incorrectCounter;
    private $numberOfIncorrect;
    private $incorrect;

    public function __construct() {
        $json = file_get_contents('php://input');
        $input = json_decode($json, TRUE);
        $this->problemText1 = $input["problem_text1"];
        $this->problemText2 = $input["problem_text2"];
        $this->sentencesCounter = $input["sentences"]["counter"];
        $this->sentences = array();
        for($i = 1; $i <= $this->sentencesCounter; $i++) {
            array_push($this->sentences, $input["sentences"][$i]);
        }
        $this->sentencesChosen = array();

        $this->optionsCounter = $input["options"]["counter"];
        $this->options = array();
        for($i = 1; $i <= $this->optionsCounter; $i++) {
            array_push($this->options, $input["options"][$i]);
        }
        $this->optionsChosen = array();

        $this->correctCounter = $input["combinations"]["correct"]["counter"];
        $this->correct = array();
        for($i = 1; $i <= $this->correctCounter; $i++) {
            array_push($this->correct, array($input["combinations"]["correct"][$i]["sentences"],
                                                    $input["combinations"]["correct"][$i]["answer"]));
        }

        $this->incorrectCounter = $input["combinations"]["incorrect"]["counter"];
        $this->incorrect = array();
        for($i = 1; $i <= $this->incorrectCounter; $i++) {
            array_push($this->incorrect, array($input["combinations"]["incorrect"][$i]["sentences"],
                                                        $input["combinations"]["incorrect"][$i]["answer"]));
        }

    }

    private function generateNumberOf() {
        $this->numberOfSentences = 5;
        $this->numberOfOptions = 10;
        $this->numberOfCorrect = rand(3,4);
        $this->numberOfIncorrect = $this->numberOfOptions - $this->numberOfCorrect;
    }

    private function chooseCorrect() {
        $correctCopy = $this->correct;
        shuffle($correctCopy);
        for($i = 0; $i < $this->correctCounter; $i++) {
            preg_match_all('/\d+/', $correctCopy[$i][0], $matches);
            $sentencesCopy = $this->sentencesChosen;
            for($j = 0; $j < count($matches[0]); $j++)
                if(!in_array($matches[0][$j], $sentencesCopy))
                    array_push($sentencesCopy, $matches[0][$j]);
            if(count($sentencesCopy) <= $this->numberOfSentences) {
                $this->sentencesChosen = $sentencesCopy;
                array_push($this->optionsChosen, array($this->options[$correctCopy[$i][1] - 1], true));
            }
            if(count($this->optionsChosen) == $this->numberOfCorrect)
                break;
        }
    }

    private function chooseIncorrect() {
        $incorrectCopy = $this->incorrect;
        shuffle($incorrectCopy);
        for($i = 0; $i < $this->incorrectCounter; $i++) {
            preg_match_all('/\d+/', $incorrectCopy[$i][0], $matches);
            $sentencesCopy = $this->sentencesChosen;
            for($j = 0; $j < count($matches[0]); $j++)
                if(!in_array($matches[0][$j], $sentencesCopy))
                    array_push($sentencesCopy, $matches[0][$j]);
            if(count($sentencesCopy) <= $this->numberOfSentences) {
                $this->sentencesChosen = $sentencesCopy;
                array_push($this->optionsChosen, array($this->options[$incorrectCopy[$i][1] - 1], false));
            }
            if(count($this->optionsChosen) == $this->numberOfOptions)
                break;
        }
    }

    private function convertSentences() {
        for($i = 0; $i < count($this->sentencesChosen); $i++) {
            $this->sentencesChosen[$i] = $this->sentences[$this->sentencesChosen[$i] - 1];
        }
    }

    public function createProblem() {
        $this->generateNumberOf();
        $this->chooseCorrect();
        $this->chooseIncorrect();
        $this->convertSentences();
        shuffle($this->optionsChosen);

        $text1 = $this->problemText1;
        $text2 = $this->problemText2;
        $sentence = array();
        $sentence["counter"] = $this->numberOfSentences;
        for($i = 1; $i <= $this->numberOfSentences; $i++)
            $sentence[$i] = $this->sentencesChosen[$i - 1];


        $option["counter"] = $this->numberOfOptions;
        for ($i = 1; $i <= $this->numberOfOptions; $i++)
            $option[$i] = array("option" => $this->optionsChosen[$i - 1][0], "answer" => $this->optionsChosen[$i - 1][1]);

        $result = array(
            "problem_text1" => $text1,
            "sentences" => $sentence,
            "problem_text2" => $text2,
            "options" => $option
        );
        return json_encode($result);
    }
}

$g = new Generator4();
print_r($g->createProblem());

