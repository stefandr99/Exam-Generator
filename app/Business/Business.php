<?php


namespace App\Business;


class Business
{
    public $exam;
    public $user;
    public $course;

    public function __construct() {
        $this->exam = new ExamBusiness();
        $this->user = new UserBusiness();
        $this->course = new CourseBusiness();
    }
}
