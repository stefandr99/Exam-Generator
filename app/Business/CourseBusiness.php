<?php


namespace App\Business;


use App\Courses;
use App\Didactic;
use Illuminate\Support\Facades\DB;

class CourseBusiness
{
    private $userBusiness;

    public function __construct()
    {
        $this->userBusiness = new UserBusiness();
    }

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
            ->get()
            ->first();

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

    public function addCourse($course) {
        $newCourse = new Courses;
        $newCourse->name = $course['name'];
        $newCourse->year = intval($course['year']);
        $newCourse->semester = intval($course['semester']);
        $newCourse->credits = intval($course['credits']);
        $newCourse->save();

        $courseId = $this->getCourseId($course['name']);

        $this->addToDidactics($course['teachers'], $courseId);
    }

    private function addToDidactics($teachers, $courseId) {
        foreach ($teachers as $teacher) {
            $teacherId = $this->userBusiness->getIdByName($teacher);

            $didactic = new Didactic;
            $didactic->teacher_id = intval($teacherId->id);
            $didactic->course_id = intval($courseId->id);
            $didactic->save();
        }
    }
}
