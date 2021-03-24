<?php

namespace App\Business;

use App\Repository\Interfaces\IUserRepository;
use App\User;
use Illuminate\Support\Facades\DB;

class UserBusiness
{
    private IUserRepository $userRepository;

    public function __construct(IUserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function getAll() {
        return $this->userRepository->all();
    }

    public function getRoleById($id): \Illuminate\Support\Collection {
        return $this->userRepository->getRoleById($id);
    }

    public function getNameById($userId) {
        return $this->userRepository->getNameById($userId);
    }

    public function getIdByName($name) {
        return $this->userRepository->getIdByName($name);
    }

    public function getYearAndSemesterById($userId): \Illuminate\Support\Collection
    {
        return $this->userRepository->getYearAndSemesterById($userId);
    }

    public function getTeachers(): \Illuminate\Support\Collection
    {
        return $this->userRepository->getTeachers();
    }

    public function search($toMatch, $criteria): \Illuminate\Support\Collection
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
    }

    public function delete($id) {
        $this->userRepository->delete($id);
    }
}
