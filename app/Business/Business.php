<?php


namespace App\Business;

use App\Repository\Eloquent\UserRepository;

class Business
{
    public $exam;
    public $user;
    public $course;

    public function __construct() {
        $this->exam = new ExamBusiness();
        $this->user = new UserBusiness(new UserRepository);
        $this->course = new CourseBusiness();
    }
}
