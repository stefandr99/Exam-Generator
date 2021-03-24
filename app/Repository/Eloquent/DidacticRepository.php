<?php


namespace App\Repository\Eloquent;


use App\Didactic;
use App\Repository\Interfaces\IDidacticRepository;
use Illuminate\Support\Facades\DB;

class DidacticRepository implements IDidacticRepository
{

    public function addTeacher($teacherId, $courseId)
    {
        $didactic = new Didactic;
        $didactic->teacher_id = intval($teacherId);
        $didactic->course_id = intval($courseId);
        $didactic->save();
    }

    public function deleteTeacher($teacherId, $courseId)
    {
        DB::table('didactics')
            ->where('teacher_id', $teacherId)
            ->where('course_id', $courseId)
            ->delete();
    }

    public function checkTeacherExistence($teacherId)
    {
        $courses = DB::table('didactics')
            ->where('teacher_id', $teacherId)
            ->get();

        return count($courses) > 0;
    }
}
