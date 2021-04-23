<?php


namespace App\Service;


class ExamServices
{
    public function createDBExercisesJSON($exam) {
        $numberOfExercises = $exam['number_of_exercises'] + 1;
        $totalPoints = 0;
        $exercises = array();

        for($exCounter = 0; $exCounter < $numberOfExercises; $exCounter++) {
            $exercises[$exCounter] = array();

            $fieldName = 'exam_exercise_' . $exCounter;
            $exercises[$exCounter][0] = $exam[$fieldName];

            $fieldName = 'points_exercise_' . $exCounter;
            $exercises[$exCounter][1] = $exam[$fieldName];
            $totalPoints += $exam[$fieldName];
        }

        return array($exercises, $totalPoints);
    }

    public function createExercisesJSON($exam) {
        $numberOfExercises = $exam['number_of_exercises'] + 1;
        $totalPoints = 0;
        $exercises = array();
        $exercises['total_count'] = $numberOfExercises;
        $exercises['exercises'] = array();

        for($exCounter = 0; $exCounter < $numberOfExercises; $exCounter++) {
            $exercises['exercises'][$exCounter] = array();

            $fieldName = 'text_exercise_' . $exCounter;
            $exercises['exercises'][$exCounter]['text'] = $exam[$fieldName];

            $fieldName = 'points_exercise_' . $exCounter;
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

        $options['generate'] = array();
        $fieldName = 'number_of_generated_options_' . $index;
        $options['generate']['total'] = $exam[$fieldName];

        $fieldName = 'correct_options_ex_' . $index;
        $options['generate']['correct'] = $exam[$fieldName];

        $fieldName = 'wrong_options_ex_' . $index;
        $options['generate']['wrong'] = $exam[$fieldName];

        $options['solution'] = $this->getExerciseOptionsSolution($index, $options['counter'], $exam);

        return $options;
    }

    private function getExerciseOptionsSolution($index, $optionCounter, $exam) {
        $solution = array();
        for($i = 0; $i < $optionCounter; $i++) {
            $solution[$i] = array();

            $fieldName = 'exercise_' . $index .'_option_' . $i;
            $solution[$i]['option'] = $exam[$fieldName];

            $fieldName = 'exercise_' . $index .'_option_' . $i . '_answer';
            $solution[$i]['answer'] = $exam[$fieldName] == "true";
        }

        return $solution;
    }

    public function getExamPenalization($exam) {
        $penalization = array();

        $penaltyType = $exam['examPenalty'];
        $penalization['type'] = $penaltyType;
        $penalization['body'] = array();

        switch ($penaltyType) {
            case 'points':
                $points = $exam['points_penalization'];
                $penalization['body']['points'] = $points;
                break;
            case 'time':
                $minutes = $exam['minutes_penalization'];
                $seconds = $exam['seconds_penalization'];
                $penalization['body']['minutes'] = $minutes;
                $penalization['body']['seconds'] = $seconds;
                break;
            case 'limitations':
                $limit = $exam['rule_limit'];
                $warnings = array_key_exists('rule_warnings', $exam);
                $penalization['body']['limit'] = $limit;
                $penalization['body']['warnings'] = $warnings;

                $limitationExceededType = $exam['examPenaltyLimit'];
                $penalization['body']['exceeded'] = $this->getPenalizationWhenExceeded($exam, $limitationExceededType);
                break;
        }

        return $penalization;
    }

    private function getPenalizationWhenExceeded($exam, $exceededType) {
        $body = array();
        $body['type'] = $exceededType;
        switch ($exceededType) {
            case 'points':
                $points = $exam['limit_points_penalization'];
                $body['points'] = $points;
                break;
            case 'time':
                $minutes = $exam['limit_minutes_penalization'];
                $seconds = $exam['limit_seconds_penalization'];
                $body['minutes'] = $minutes;
                $body['seconds'] = $seconds;
                break;
        }
        return $body;
    }
}
