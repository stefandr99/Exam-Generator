<?php


namespace App\Repository\Interfaces;

interface ICourseRepository
{
    public function getAll();
    public function getIdByName($name);
    public function getNameById($id);
    public function getDatabasesId();
    public function getCourseId($courseId);
    public function getCoursesTeachers($courses);
    public function getCoursesNoTeachers($courses);

    public function search($toMatch);

    public function create($course);
    public function delete($id);
}
