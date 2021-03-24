<?php


namespace App\Repository\Interfaces;


interface IDidacticRepository
{
    public function addTeacher($teacherId, $courseId);
    public function deleteTeacher($teacherId, $courseId);
    public function checkTeacherExistence($teacherId);
}
