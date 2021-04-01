<?php


namespace App\Repository\Eloquent;


use App\Exam;
use App\Repository\Interfaces\IExamRepository;
use Illuminate\Support\Facades\DB;

class ExamRepository implements IExamRepository
{

    public function create($exam)
    {
        $newExam = new Exam;
        $newExam->course_id = $exam['id'];
        $newExam->type = $exam['type'];
        $newExam->starts_at = $exam['starts_at'];
        $newExam->ends_at = $exam['ends_at'];
        $newExam->hours = $exam['hours'];
        $newExam->minutes = $exam['minutes'];
        $newExam->number_of_exercises = $exam['number_of_exercises'];
        $newExam->exercises_type = $exam['exercises_type'];
        $newExam->total_points = $exam['total_points'];
        $newExam->minimum_points = $exam['minimum_points'];
        $newExam->penalization = $exam['penalization'];
        $newExam->save();
    }

    public function getInfoById($id)
    {
        return DB::table('exams')
            ->join('courses', 'courses.id', '=', 'exams.course_id')
            ->select('courses.name as course_name', 'type', 'starts_at', 'hours', 'minutes',
                'number_of_exercises', 'exercises_type', 'total_points', 'penalization')
            ->where('exams.id', $id)
            ->get();
    }

    public function getResult($examId, $userId)
    {
        return DB::table('subjects as s')
            ->join('exams as e', 'e.id', '=', 's.exam_id')
            ->join('courses as c', 'c.id', '=', 'e.course_id')
            ->select('c.name as course_name', 'e.type', 'e.starts_at', 'e.number_of_exercises', 'e.total_points', 'e.minimum_points',
                's.exercises', 's.obtained_points', 's.student_answers', 's.results')
            ->where('s.exam_id', $examId)
            ->where('s.user_id', $userId)
            ->get()
            ->first();
    }

    public function getAllForTeachers($userId)
    {
        return DB::table('users')
            ->join('didactics', 'users.id', '=', 'didactics.teacher_id')
            ->join('courses', 'courses.id', '=', 'didactics.course_id')
            ->join('exams', 'courses.id', '=', 'exams.course_id')
            ->select('exams.id as exam_id', 'users.name as teacher_name', 'courses.name as course_name',
                'exams.type', 'exams.starts_at', 'exams.ends_at', 'exams.hours', 'exams.minutes', 'exams.number_of_exercises',
                'exams.total_points', 'exams.minimum_points')
            ->where('users.id', $userId)
            ->where('exams.ends_at', '>', now())
            ->orderBy('exams.starts_at')
            ->get();
    }

    public function getAllForStudents($userId, $year, $semester)
    {
        return DB::table('exams')
            ->join('courses', 'courses.id', '=', 'exams.course_id')
            ->select('exams.id as exam_id', 'courses.name as course_name', 'exams.type', 'exams.starts_at', 'exams.ends_at',
                'exams.hours', 'exams.minutes', 'exams.number_of_exercises', 'exams.total_points', 'exams.minimum_points')
            ->where('courses.year', $year)
            ->where('courses.semester', $semester)
            ->where('exams.ends_at', '>', DB::raw("now()"))
            ->orderBy('exams.starts_at')
            ->get();
    }

    public function getTeachersByExam($examId)
    {
        return DB::table('users')
            ->join('didactics', 'users.id', '=', 'didactics.teacher_id')
            ->join('courses', 'courses.id', '=', 'didactics.course_id')
            ->join('exams', 'courses.id', '=', 'exams.course_id')
            ->select('users.name')
            ->where('exams.id', $examId)
            ->orderBy('users.created_at')
            ->get();
    }

    public function getExamById($id)
    {
        return DB::table('exams as e')
            ->join('courses as c', 'c.id', '=', 'e.course_id')
            ->select('c.name as course_name', 'e.*')
            ->where('e.id', $id)
            ->get();
    }

    public function update($exam)
    {
        DB::table('exams')
            ->where('id', $exam['id'])
            ->update([
                'course_id' => $exam['course_id'],
                'type' => $exam['info'],
                'starts_at' => $exam['starts_at'],
                'ends_at' => $exam['ends_at'],
                'hours' => $exam['hours'],
                'minutes' => $exam['minutes'],
                'number_of_exercises' => $exam['number_of_exercises'],
                'exercises_type' => $exam['exercises_type'],
                'total_points' => $exam['total_points'],
                'minimum_points' => $exam['minimum_points']
            ]);
    }
}
