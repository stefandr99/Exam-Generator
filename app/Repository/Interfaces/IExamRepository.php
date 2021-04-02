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
    public function getTemporalStats($id);

    public function update($exam);



}