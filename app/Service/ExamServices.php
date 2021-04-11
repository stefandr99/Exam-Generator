<?php


namespace App\Service;


class ExamServices
{
    public function createExercisesJSON($exam) {
        $numberOfExercises = $exam['number_of_exercises'] + 1;
        $totalPoints = 0;
        $exercises = array();
        $exercises['total_count'] = $numberOfExercises;
        $exercises['exercises'] = array();

        for($exCounter = 0; $exCounter <= $numberOfExercises; $exCounter++) {
            $exercises['exercises'][$exCounter] = array();

            $fieldName = 'text_exercise_' . $exCounter;
            $exercises['exercises'][$exCounter]['text'] = $exam[$fieldName];

            $fieldName = 'points_ex_' . $exCounter;
            $exercises['exercises'][$exCounter]['points'] = $exam[$fieldName];
            $totalPoints += $exam[$fieldName];

            $exercises['exercises'][$exCounter]['options'] = $this->getExerciseOptions($exCounter, $exam);
        }

        return array($exercises, $totalPoints);

    }

    private function getExerciseOptions($index, $exam) {
        $options = array();
        $fieldName = 'number_of_options_exercise_' . $index;
        $options['counter'] = $exam[$fieldName] + 1;

        $fieldName = 'shuffle_' . $index;
        if(array_key_exists($fieldName, $exam))
            $options['shuffle'] = true;
        else $options['shuffle'] = false;

        $options['generate'] = array();
        $fieldName = 'number_of_options_exercise_' . $index;
        $options['generate']['total'] = $exam[$fieldName];

        $fieldName = 'correct_options_ex_' . $index;
        $options['generate']['correct'] = $exam[$fieldName];

        $fieldName = 'wrong_options_ex_' . $index;
        $options['generate']['wrong'] = $exam[$fieldName];

        $options['body'] = $this->getExerciseOptionsBody($index, $options['counter'], $exam);

        return $options;
    }

    private function getExerciseOptionsBody($index, $optionCounter, $exam) {
        $body = array();
        $body['counter'] = $optionCounter;
        $body['body'] = array();
        for($i = 0; $i <= $optionCounter; $i++) {
            $body['body'][$i] = array();

            $fieldName = 'exercise_' . $index .'_option_' . $i;
            $body['body'][$i]['option_text'] = $exam[$fieldName];

            $fieldName = 'exercise_' . $index .'_option_' . $i . '_answer';
            $body['body'][$i]['answer'] = $exam[$fieldName];
        }

        return $body;
    }

}
