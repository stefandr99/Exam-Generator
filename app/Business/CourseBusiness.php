<?php


namespace App\Business;

use App\Repository\Interfaces\ICourseRepository;

class CourseBusiness
{
    private $courseRepository;

    public function __construct(ICourseRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    public function getAll() {
        return $this->courseRepository->getAll();
    }

    public function getIdByName($name) {
        return $this->courseRepository->getIdByName($name);
    }

    private function getNameById($id) {
        return $this->courseRepository->getNameById($id);
    }

    public function getDatabasesId() {
        return $this->courseRepository->getDatabasesId();
    }

    public function getCourseId($courseId) {
        return $this->courseRepository->getCourseId($courseId);
    }

    public function addCourse($course) {
        $this->courseRepository->create($course);
    }

    public function getTeachersAndNoTeachersByCourses($courses) {
        $teachers = array();
        foreach ($courses as $c) {
            $courseId = $c->id;
            $courseTeachers = $this->courseRepository->getCoursesTeachers($courseId);
            $teachers[$courseId] = $courseTeachers;
        }

        $noTeachers = array();
        foreach ($courses as $c) {
            $courseId = $c->id;
            $courseTeachers = $this->courseRepository->getCoursesNoTeachers($courseId);
            $noTeachers[$courseId] = $courseTeachers;
        }

        $result = array('teachers' => $teachers, 'noTeachers' => $noTeachers);
        return $result;
    }

    public function search($toMatch) {
        $courses = $this->courseRepository->search($toMatch);

        $teachers = $this->getTeachersAndNoTeachersByCourses($courses);

        $result = array(
            'courses' => $courses,
            'teachers' => $teachers['teachers'],
            'noTeachers' => $teachers['noTeachers']
        );
        return $result;
    }
}
