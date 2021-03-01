<?php


namespace App\Business;


use Illuminate\Support\Facades\DB;

class CourseBusiness
{
    public function getCourseIdByName($name) {
        $course = DB::table('courses')
            ->select('id')
            ->where('name', $name)
            ->get();

        return $course;
    }

    public function getCourseId($courseName) {
        $result = DB::table('courses')
            ->select('id')
            ->where('name', $courseName)
            ->get();

        return $result;
    }

    private function getCourseName($examId) {
        $result = DB::table('exams')
            ->join('courses', 'courses.id', '=', 'exams.course_id')
            ->select('courses.name')
            ->where('exams.id', $examId)
            ->get();
        return $result;
    }
}
