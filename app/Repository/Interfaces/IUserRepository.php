<?php


namespace App\Repository\Interfaces;


interface IUserRepository
{
    /**
     * Getters
     */
    public function all();
    public function getRoleById($id);
    public function getNameById($id);
    public function getIdByName($name);
    public function getYearAndSemesterById($id);
    public function getTeachers();

    /**
     * Search
     */
    public function search($toMatch, $criteria);

    /**
     * Updates
     */
    public function changeRole($id, $newRole);
    public function passToNextSemester();
    public function passToNextYear();

    /**
     * Deletes
     */
    public function delete($id);
}
