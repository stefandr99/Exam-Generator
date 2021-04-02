<?php


namespace App\Repository\Eloquent;

use App\Repository\Interfaces\ISubjectRepository;
use App\Subject;
use Illuminate\Support\Facades\DB;

class SubjectRepository implements ISubjectRepository
{

    public function createSubject($subject)
    {
        $newSubject = new Subject;
        $newSubject->user_id = $subject['user_id'];
        $newSubject->exam_id = $subject['exam_id'];
        $newSubject->exercises = $subject['exercises'];
        $newSubject->total_points = $subject['total_points'];
        $newSubject->save();
    }

    public function getSubjectExercises($examId, $userId)
    {
        return DB::table('subjects')
            ->select('exercises', 'total_points')
            ->where('user_id', $userId)
            ->where('exam_id', $examId)
            ->get();
    }

    public function updateSubject($examId, $userId, $subjectWithAnswers)
    {
        DB::table('subjects')
            ->where('user_id', $userId)
            ->where('exam_id', $examId)
            ->update([
                'obtained_points' => $subjectWithAnswers['obtained_points'],
                'student_answers' => $subjectWithAnswers['student_answers'],
                'results' => $subjectWithAnswers['results']
            ]);
    }

    public function getPenalizationInfoById($id)
    {
        return DB::table('exams')
            ->select('penalization', 'starts_at', 'ends_at', 'hours', 'minutes')
            ->where('id', $id)
            ->get()
            ->first();
    }
}
