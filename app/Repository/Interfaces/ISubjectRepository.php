<?php


namespace App\Repository\Interfaces;


interface ISubjectRepository
{
    public function createSubject($subject);

    public function getSubjectExercises($examId, $userId);

    public function updateSubject($examId, $userId, $subjectWithAnswers, $forcedSubmit, $submitDate, $timePromoted);

    public function getPenalizationInfoById($id);

    public function getExamDate($examId);
}
