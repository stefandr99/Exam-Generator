<?php


namespace App\Repository\Eloquent;


use App\Courses;
use App\Repository\Interfaces\ICourseRepository;
use Illuminate\Support\Facades\DB;

class CourseRepository implements ICourseRepository
{
    public function getAll()
    {
        return DB::table('courses')
            ->orderBy('year')
            ->orderBy('semester')
            ->orderBy('credits')
            ->get();
    }

    public function getIdByName($name)
    {
        return DB::table('courses')
            ->select('id')
            ->where('name', $name)
            ->get()
            ->first();
    }

    public function getNameById($id)
    {
        return DB::table('exams')
            ->join('courses', 'courses.id', '=', 'exams.course_id')
            ->select('courses.name')
            ->where('exams.id', $id)
            ->get();
    }

    public function getDatabasesId() {
        return DB::table('courses')
            ->select('id')
            ->where('name', 'Baze de date')
            ->get()
            ->first();
    }

    public function getCourseId($courseId) {
        return DB::table('courses')
            ->select('id')
            ->where('id', $courseId)
            ->get()
            ->first();
    }

    public function getCoursesTeachers($courseId)
    {
        return DB::table('users as u')
            ->join('didactics as d', 'u.id', '=', 'd.teacher_id')
            ->where('d.course_id', $courseId)
            ->select('u.id', 'name')
            ->get();
    }

    public function getCoursesNoTeachers($courseId)
    {
        return DB::select(DB::raw('select users.id, name from users
                                    where users.role = 2 and users.id not in
                                     (select DISTINCT d.teacher_id from didactics as d where d.course_id = ' . $courseId. ')'));
    }

    public function search($toMatch)
    {
        return DB::table('courses')
            ->where('name', 'like', '%'.$toMatch.'%')
            ->get();
    }

    public function create($course)
    {
        $newCourse = new Courses;
        $newCourse->name = $course['name'];
        $newCourse->year = intval($course['year']);
        $newCourse->semester = intval($course['semester']);
        $newCourse->credits = intval($course['credits']);
        $newCourse->save();
    }

    public function delete($id) {
        DB::table('courses')
            ->where('id', $id)
            ->delete();
    }
}
