<?php


namespace App\Repository\Interfaces;


interface IExamRepository
{
    public function create($exam);

    public function getInfoById($id);
    public function getResult($examId, $userId);
    public function getAllForTeachers($userId, $semester);
    public function getAllForStudents($userId, $year, $semester);
    public function getTeachersByExam($examId);
    public function getExamById($id);
    public function getExamStats($id);
    public function getFilteredExamStats($id, $filter);
    public function history($id);

    public function getExamStatsBySearch($examId, $name);

    public function update($exam);

    public function promoteByTimeStudent($examId, $userId);
    public function undoPromoteByTimeStudent($examId, $userId);
}
