<?php


namespace App\Business;


class Business
{
    public ExamBusiness $exam;
    public UserBusiness $user;
    public CourseBusiness $course;

    public function __construct() {
        $this->exam = new ExamBusiness();
        $this->user = new UserBusiness();
        $this->course = new CourseBusiness();
    }
}
