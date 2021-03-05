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

    public function showCourses() {
        $coursesAndTeachers = $this->business->course->getAll();

        $courses = $coursesAndTeachers['courses'];
        $teachers = $coursesAndTeachers['teachers'];
        $noTeachers = $coursesAndTeachers['noTeachers'];

        return view('course/showAll', [
            'courses' => $courses,
            'teachers' => $teachers,
            'noTeachers' => $noTeachers
        ]);
    }

    public function search(Request $request) {
        $coursesAndTeachers = $this->business->course->search($request->name);

        $courses = $coursesAndTeachers['courses'];
        $teachers = $coursesAndTeachers['teachers'];
        $noTeachers = $coursesAndTeachers['noTeachers'];

        return view('course/showAll', [
            'courses' => $courses,
            'teachers' => $teachers,
            'noTeachers' => $noTeachers
        ]);
    }

    public function addTeacherToCourse(Request $request) {
        if($request->teacherToAdd != 0)
            $this->business->course->addTeacherToCourse($request->teacherToAdd, $request->courseId);

        return redirect()->route('show_courses');
    }

    public function deleteTeacherFromCourse(Request $request) {
        if($request->teacherToDelete != 0)
            $this->business->course->deleteTeacherFromCourse($request->teacherToDelete, $request->courseId);

        return redirect()->route('show_courses');
    }
}
