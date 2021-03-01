<?php

namespace App\Http\Controllers;

use App\Business\Business;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    private $business;

    public function __construct()
    {
        $this->middleware('auth');
        $this->business = new Business();
    }

    public function addCourse() {
        $teachers = $this->business->user->getAllTeachers();

        return view('course/add', ['teachers' => $teachers]);
    }
}
