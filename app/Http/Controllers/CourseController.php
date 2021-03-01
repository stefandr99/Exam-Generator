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

    public function prepareNewCourse() {
        $teachers = $this->business->user->getAllTeachers();

        return view('course/prepare', ['teachers' => $teachers]);
    }

    public function addNewCourse(Request $request) {
        if ($request->ajax()) {
            $name = $request->input('name');
            $teachers = json_decode($request->input('teachers'), true);
            $year = $request->input('year');
            $semester = $request->input('semester');
            $credits = $request->input('credits');
            $info = array(
                'name' => $name,
                'teachers' => $teachers,
                'year' => $year,
                'semester' => $semester,
                'credits' => $credits
            );
            $this->business->course->addCourse($info);
        }
    }
}
