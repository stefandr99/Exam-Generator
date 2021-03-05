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

    public function getAll() {
        $courses = DB::table('courses')
            ->orderBy('year')
            ->orderBy('semester')
            ->orderBy('credits')
            ->get();

        $teachers = $this->getTeachersArray($courses);

        $result = array(
            'courses' => $courses,
            'teachers' => $teachers['teachers'],
            'noTeachers' => $teachers['noTeachers']
        );
        return $result;
    }

    private function getTeachersArray($courses) {
        $teachersArr = array();
        $noTeachersArr = array();

        foreach ($courses as $c) {
            $courseId = $c->id;
            $courseTeachers = $this->userBusiness->getTeachersFromCourseId($courseId);
            $teachersArr[$courseId] = $courseTeachers;

            $courseNoTeachers = $this->userBusiness->getNoTeachersFromCourseId($courseId);
            $noTeachersArr[$courseId] = $courseNoTeachers;
        }

        $result = array('teachers' => $teachersArr, 'noTeachers' => $noTeachersArr);
        return $result;
    }

    public function search($name) {
        $courses = DB::table('courses')
            ->where('name', 'like', '%'.$name.'%')
            ->get();

        $teachers = $this->getTeachersArray($courses);

        $result = array(
            'courses' => $courses,
            'teachers' => $teachers['teachers'],
            'noTeachers' => $teachers['noTeachers']
        );
        return $result;
    }

    public function addTeacherToCourse($teacherId, $courseId) {
        $didactic = new Didactic;
        $didactic->course_id = $courseId;
        $didactic->teacher_id = $teacherId;
        $didactic->save();
    }

    public function deleteTeacherFromCourse($teacherId, $courseId) {
        DB::table('didactics')
            ->where('teacher_id', $teacherId)
            ->where('course_id', $courseId)
            ->delete();
    }
}
