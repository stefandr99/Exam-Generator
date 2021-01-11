<?php


namespace App\Latex;


class LatexGenerator
{
    private static $begin;
    private static $end;
    public static function generate($exercises) {
        self::$begin = file_get_contents("./resources/latex_templates/begin.txt",0,null,null);
        self::$end = file_get_contents("./resources/latex_templates/end.txt",0,null,null);
        return self::$begin . self::exercise1($exercises[0]) . self::exercise2($exercises[1]) .
            self::exercise3($exercises[2]) . self::exercise4($exercises[3]) . self::$end;
    }

    private static function exercise1($exercise) {
        $arrow = '$\rightarrow$';

        $text = "\section*{Exercitiul 1 (3 p.)}" . "\n" .
            "Sa se stabileasca care dintre urmatoarele dependente sunt satisfacute de catre relatia r data tabelar:" . "\n";

        $table = "\begin{center} \n" . "\begin{tabular}{c c c} \n" . "r: \n" . "\begin{tabular}{ c c c c c} \n";

        for ($i = 0; $i < count($exercise['attributes']) - 1; $i++) {
            $table .= $exercise['attributes'][$i] . " & ";
        }
        $table .= $exercise['attributes'][$i] . "\\\\ \n";
        $table .= "\hline \n";
        for($i = 1; $i <= $exercise['relation']['counter']; $i++) {
            for($j = 0; $j < count($exercise['relation']['table'][$i]) - 1; $j++) {
                $table .= $exercise['relation']['table'][$i][$j] . " & ";
            }
            $table .= $exercise['relation']['table'][$i][$j] . " \\\\ \n";
        }
        $table .= "\hline \n" . "\\end{tabular} \n";

        $options = "\begin{tabular}{p{3cm}} \n" . "\begin{enumerate}[label={\alph*)}] \n" . "\itemsep-0.5em \n";
        for($i = 1; $i <= $exercise['options']['counter']; $i++) {
            $withArrow = str_replace("->", $arrow, $exercise['options']['solution'][$i]['option']);
            $options .= "\item " . $withArrow . "\n";
        }
        $options .= "\\end{enumerate} \n" . "\\end{tabular} \n \n";
        $options .= "\\end{tabular} \n" . "\\end{center} \n \n";

        return $text . $table . $options;
    }

    private static function exercise2($exercise) {
        $arrow = '$\rightarrow$';

        $text1 = "\section*{Exercitiul 2 (3 p.)}" . "\n" . $exercise['problem_text1'] . "\n";

        $sentences = "\begin{itemize} \n \itemsep-0.5em \n";
        for ($i = 1; $i <= $exercise['sentences']['counter']; $i++) {
            $sentences .= "\item " . $exercise['sentences'][$i] . "\n";
        }
        $sentences .= "\\end{itemize} \n";

        $text2 = $exercise['problem_text2'] . "\n";

        $options = "\begin{enumerate}[label={\alph*)}] \n" . "\itemsep-0.5em \n";
        for ($i = 1; $i <= $exercise['options']['counter']; $i++) {
            $withArrow = str_replace("->", $arrow, $exercise['options']['solution'][$i]['option']);
            $options .= "\item " . $withArrow . "\n";
        }
        $options .= "\\end{enumerate} \n";

        return $text1 . $sentences . $text2 . $options;
    }

    private static function exercise3($exercise) {
        $arrow = '$\rightarrow$';

        $text = "\section*{Exercitiul 3 (3 p.)} \n" . "Care sunt adevarate pentru schema de relatie ";
        $text .= $exercise['relationship'] . " si \\\\ \n";

        $sigma = "$\Sigma$ = \{ ";
        for($i = 1; $i < $exercise['sigma']['counter']; $i++) {
            $sigma .= $exercise['sigma']['dependencies'][$i]['leftside'] . " " . $arrow = '$\rightarrow$' . " " .
                    $exercise['sigma']['dependencies'][$i]['rightside'] . ", ";
        }
        $sigma .= $exercise['sigma']['dependencies'][$i]['leftside'] . " " . $arrow = '$\rightarrow$' . " " .
                $exercise['sigma']['dependencies'][$i]['rightside'] . " \} \n";

        $options = "\begin{enumerate}[label={\alph*)}] \n" . "\itemsep-0.5em \n";
        for ($i = 1; $i <= $exercise['options']['counter']; $i++) {
            $options .= "\item \(" . $exercise['options']['solution'][$i]['attr'] . "^+\) = ";
            $correctBracket = str_replace("}", "\}", $exercise['options']['solution'][$i]['attr+']);
            $options .= "\\" . $correctBracket . "\n";
        }
        $options .= "\\end{enumerate} \n";

        return $text . $sigma . $options;
    }

    private static function exercise4($exercise) {
        $arrow = '$\rightarrow$';

        $text = "\section*{Exercitiul 4 (3 p.)} \n";
        $preparedText = $exercise['exercise'];
        $preparedText = str_replace("&Sigma;", "\\\\ $\Sigma$", $preparedText);
        $preparedText = str_replace("&Delta;", "\\\\ $\Delta$", $preparedText);
        $preparedText = str_replace(".", ".\\\\", $preparedText);
        $preparedText = str_replace("->", $arrow, $preparedText);
        $preparedText = str_replace("{", "\{", $preparedText);
        $preparedText = str_replace("}", "\}", $preparedText);
        $text .= $preparedText . "\n";

        $options = "\begin{enumerate}[label={\alph*)}] \n" . "\itemsep-0.5em \n";
        for ($i = 1; $i <= $exercise['options']['counter']; $i++) {
            $options .= "\item " . $exercise['options']['solution'][$i]['option'] . "\n";
        }
        $options .= "\\end{enumerate} \n";

        return $text . $options;
    }
}
