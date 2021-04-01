<?php

namespace App\Business;

use App\Repository\Interfaces\IUserRepository;

class UserBusiness
{
    private $userRepository;

    public function __construct(IUserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function getAll() {
        return $this->userRepository->getAll();
    }

    public function getRoleById($id) {
        return $this->userRepository->getRoleById($id);
    }

    public function getNameById($userId) {
        return $this->userRepository->getNameById($userId);
    }

    public function getIdByName($name) {
        return $this->userRepository->getIdByName($name);
    }

    public function getYearAndSemesterById($userId)
    {
        return $this->userRepository->getYearAndSemesterById($userId);
    }

    public function getTeachers()
    {
        return $this->userRepository->getTeachers();
    }

    public function search($toMatch, $criteria)
    {
        return $this->userRepository->search($toMatch, $criteria);
    }

    public function changeRole($id, $newRole) {
        $this->userRepository->changeRole($id, $newRole);
    }

    public function passToNextSemester() {
        $this->userRepository->passToNextSemester();
    }

    public function passToNextYear() {
        $this->userRepository->passToNextYear();
        $this->userRepository->deleteGraduated();
    }

    public function delete($id) {
        $this->userRepository->delete($id);
    }
}
