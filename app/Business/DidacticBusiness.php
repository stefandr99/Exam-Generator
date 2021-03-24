<?php


namespace App\Business;

use App\Repository\Eloquent\UserRepository;
use App\Repository\Interfaces\IDidacticRepository;

class DidacticBusiness
{
    private IDidacticRepository $didacticRepository;

    public function __construct(IDidacticRepository $didacticRepository) {
        $this->didacticRepository = $didacticRepository;
    }

    public function addTeacher($teacherId, $courseId) {
        $this->didacticRepository->addTeacher($teacherId, $courseId);
    }

    public function deleteTeacher($teacherId, $courseId) {
        $this->didacticRepository->deleteTeacher($teacherId, $courseId);
    }

    public function addTeachersToCourse($teachers, $courseId) {
        $userRepository = new UserRepository();

        $courseId = intval($courseId->id);
        foreach ($teachers as $teacher) {
            $teacherId = $userRepository->getIdByName($teacher);
            $teacherId = intval($teacherId->id);

            $this->didacticRepository->addTeacher($teacherId, $courseId);
        }
    }
}
