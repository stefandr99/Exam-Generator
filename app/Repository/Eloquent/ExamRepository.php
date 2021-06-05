<?php


namespace App\Repository\Eloquent;


use App\Exam;
use App\Repository\Interfaces\IExamRepository;
use DateInterval;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\DB;

class ExamRepository implements IExamRepository
{

    public function create($exam) {
        $newExam = new Exam;
        $newExam->course_id = $exam['courseId'];
        $newExam->type = $exam['type'];
        $newExam->starts_at = $exam['startsAt'];
        $newExam->ends_at = $exam['endsAt'];
        $newExam->hours = $exam['hours'];
        $newExam->minutes = $exam['minutes'];
        $newExam->number_of_exercises = $exam['totalExercises'];
        $newExam->exercises = $exam['exercises'];
        $newExam->total_points = $exam['totalPoints'];
        $newExam->minimum_points = $exam['minimumPoints'];
        $newExam->penalization = $exam['penalization'];
        $newExam->save();
    }

    public function getInfoById($id) {
        return DB::table('exams')
            ->join('courses', 'courses.id', '=', 'exams.course_id')
            ->select('courses.name as course_name', 'type', 'starts_at', 'hours', 'minutes',
                'number_of_exercises', 'exercises', 'total_points', 'penalization')
            ->where('exams.id', $id)
            ->get()
            ->first();
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

    public function getAllForTeachers($userId, $semester)
    {
        $presentDate = new DateTime("now");
        $presentDate->add(new DateInterval('PT3H'));

        return DB::table('users')
            ->join('didactics', 'users.id', '=', 'didactics.teacher_id')
            ->join('courses', 'courses.id', '=', 'didactics.course_id')
            ->join('exams', 'courses.id', '=', 'exams.course_id')
            ->select('exams.id as exam_id', 'users.name as teacher_name', 'courses.name as course_name',
                'exams.type', 'exams.starts_at', 'exams.ends_at', 'exams.hours', 'exams.minutes', 'exams.number_of_exercises',
                'exams.total_points', 'exams.minimum_points')
            ->where('users.id', $userId)
            ->where('courses.semester', $semester)
            ->where('exams.ends_at', '>', $presentDate)
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

    public function getlast30DaysExamsForTeacher($userId, $semester) {
        $presentDate = new DateTime("now", new DateTimeZone('Europe/Tiraspol'));

        $lastThirtyDaysDate = new DateTime("now", new DateTimeZone('Europe/Tiraspol'));
        $lastThirtyDaysDate->sub(new DateInterval('P30D'));

        return DB::table('users')
            ->join('didactics', 'users.id', '=', 'didactics.teacher_id')
            ->join('courses', 'courses.id', '=', 'didactics.course_id')
            ->join('exams', 'courses.id', '=', 'exams.course_id')
            ->select('exams.id as exam_id', 'users.name as teacher_name', 'courses.name as course_name',
                'exams.type', 'exams.starts_at', 'exams.ends_at', 'exams.hours', 'exams.minutes', 'exams.number_of_exercises',
                'exams.total_points', 'exams.minimum_points')
            ->where('users.id', $userId)
            ->where('courses.semester', $semester)
            ->where('exams.starts_at', '>', $lastThirtyDaysDate)
            ->where('exams.ends_at', '<', $presentDate)
            ->orderBy('exams.starts_at', 'desc')
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
            ->get()
            ->first();
    }

    public function update($exam)
    {
        DB::table('exams')
            ->where('id', $exam['id'])
            ->update([
                'type' => $exam['type'],
                'starts_at' => $exam['starts_at'],
                'ends_at' => $exam['ends_at'],
                'hours' => $exam['hours'],
                'minutes' => $exam['minutes'],
                'number_of_exercises' => $exam['number_of_exercises'],
                'exercises' => $exam['exercises'],
                'total_points' => $exam['total_points'],
                'minimum_points' => $exam['minimum_points'],
                'penalization' => $exam['penalization']
            ]);
    }

    public function getExamInfoForStudentStats($id) {
        return DB::table('exams')
            ->join('courses', 'courses.id', '=', 'exams.course_id')
            ->select('exams.id as exam_id', 'exams.type', 'courses.name as course_name', 'exams.starts_at', 'exams.ends_at',
                'exams.total_points', 'exams.minimum_points')
            ->where('exams.id', $id)
            ->get()
            ->first();
    }

    public function getExamStats($id) {
        $examInformation = $this->getExamInfoForStudentStats($id);

        $subjectInfo = $this->getAllSubjectStats($id);

        return array(
            'exam' => $examInformation,
            'subject' => $subjectInfo
        );
    }

    private function getAllSubjectStats($id) {
        return DB::table('users')
            ->join('subjects', 'users.id', '=', 'subjects.user_id')
            ->join('exams', 'exams.id', '=', 'subjects.exam_id')
            ->select('subjects.id', 'users.id as user_id', 'users.name as user_name', 'users.group as student_group',
                'subjects.obtained_points', 'subjects.submitted_at', 'subjects.forced_submit', 'subjects.penalizations',
                'subjects.time_promoted',
                DB::raw('TIMEDIFF(subjects.submitted_at, exams.ends_at) as time_diff'),
                DB::raw('TIMESTAMPDIFF(SECOND, exams.ends_at, subjects.submitted_at) as second_diff')) // negativ daca nu a intarziat, pozitiv altfel
            ->where('subjects.exam_id', $id)
            ->orderBy('subjects.submitted_at', 'desc')
            ->paginate(50);
    }

    public function getFilteredExamStats($id, $filter) {
        $examInformation = $this->getExamInfoForStudentStats($id);

        switch ($filter) {
            case 'all':
                $subjectInfo = $this->getAllSubjectStats($id);
                break;
            case 'promoted':
                $subjectInfo = $this->getPromotedSubjectsFiltered($id);
                break;
            case 'failed':
                $subjectInfo = $this->getUnpromotedSubjectsFiltered($id);
                break;
            case 'first_level_lateness':
                $subjectInfo = $this->getFirstLevelLatenessSubjectsFiltered($id);
                break;
            case 'second_level_lateness':
                $subjectInfo = $this->getSecondLevelLatenessSubjectsFiltered($id);
                break;
            case 'third_level_lateness':
                $subjectInfo = $this->getThirdLevelLatenessSubjectsFiltered($id);
                break;
            default:
                $subjectInfo = $this->getAllSubjectStats($id);
        }

        return array(
            'exam' => $examInformation,
            'subject' => $subjectInfo
        );
    }

    private function getPromotedSubjectsFiltered($id) {
        return DB::table('users')
            ->join('subjects', 'users.id', '=', 'subjects.user_id')
            ->join('exams', 'exams.id', '=', 'subjects.exam_id')
            ->select('subjects.id', 'users.id as user_id', 'users.name as user_name', 'users.group as student_group',
                'subjects.obtained_points', 'subjects.submitted_at', 'subjects.forced_submit', 'subjects.penalizations',
                'subjects.time_promoted',
                DB::raw('TIMEDIFF(subjects.submitted_at, exams.ends_at) as time_diff'),
                DB::raw('TIMESTAMPDIFF(SECOND, exams.ends_at, subjects.submitted_at) as second_diff')) // negativ daca nu a intarziat, pozitiv altfel
            ->where('subjects.exam_id', $id)
            ->where('subjects.obtained_points', '>=', 'exams.minimum_points')
            ->where('subjects.time_promoted', 1)
            ->orderBy('subjects.submitted_at', 'desc')
            ->paginate(50);
    }

    private function getUnpromotedSubjectsFiltered($id) {
        return DB::table('users')
            ->join('subjects', 'users.id', '=', 'subjects.user_id')
            ->join('exams', 'exams.id', '=', 'subjects.exam_id')
            ->select('subjects.id', 'users.id as user_id', 'users.name as user_name', 'users.group as student_group',
                'subjects.obtained_points', 'subjects.submitted_at', 'subjects.forced_submit', 'subjects.penalizations',
                'subjects.time_promoted',
                DB::raw('TIMEDIFF(subjects.submitted_at, exams.ends_at) as time_diff'),
                DB::raw('TIMESTAMPDIFF(SECOND, exams.ends_at, subjects.submitted_at) as second_diff')) // negativ daca nu a intarziat, pozitiv altfel
            ->where('subjects.exam_id', $id)
            ->where('subjects.obtained_points', '<', 'exams.minimum_points')
            ->orderBy('subjects.submitted_at', 'desc')
            ->paginate(50);
    }

    private function getFirstLevelLatenessSubjectsFiltered($id) {
        return DB::table('users')
            ->join('subjects', 'users.id', '=', 'subjects.user_id')
            ->join('exams', 'exams.id', '=', 'subjects.exam_id')
            ->select('subjects.id', 'users.id as user_id', 'users.name as user_name', 'users.group as student_group',
                'subjects.obtained_points', 'subjects.submitted_at', 'subjects.forced_submit', 'subjects.penalizations',
                'subjects.time_promoted',
                DB::raw('TIMEDIFF(subjects.submitted_at, exams.ends_at) as time_diff'),
                DB::raw('TIMESTAMPDIFF(SECOND, exams.ends_at, subjects.submitted_at) as second_diff')) // negativ daca nu a intarziat, pozitiv altfel
            ->where('subjects.exam_id', $id)
            ->where('subjects.time_promoted', 1)
            ->whereRaw('TIMESTAMPDIFF(SECOND, exams.ends_at, subjects.submitted_at) > 0')
            ->whereRaw('TIMESTAMPDIFF(SECOND, exams.ends_at, subjects.submitted_at) <= 30')
            ->orderBy('subjects.submitted_at', 'desc')
            ->paginate(50);
    }

    private function getSecondLevelLatenessSubjectsFiltered($id) {
        return DB::table('users')
            ->join('subjects', 'users.id', '=', 'subjects.user_id')
            ->join('exams', 'exams.id', '=', 'subjects.exam_id')
            ->select('subjects.id', 'users.id as user_id', 'users.name as user_name', 'users.group as student_group',
                'subjects.obtained_points', 'subjects.submitted_at', 'subjects.forced_submit', 'subjects.penalizations',
                'subjects.time_promoted',
                DB::raw('TIMEDIFF(subjects.submitted_at, exams.ends_at) as time_diff'),
                DB::raw('TIMESTAMPDIFF(SECOND, exams.ends_at, subjects.submitted_at) as second_diff')) // negativ daca nu a intarziat, pozitiv altfel
            ->where('subjects.exam_id', $id)
            ->whereRaw('TIMESTAMPDIFF(SECOND, exams.ends_at, subjects.submitted_at) > 30')
            ->whereRaw('TIMESTAMPDIFF(SECOND, exams.ends_at, subjects.submitted_at) < 180')
            ->orderBy('subjects.submitted_at', 'desc')
            ->paginate(50);
    }

    private function getThirdLevelLatenessSubjectsFiltered($id) {
        return DB::table('users')
            ->join('subjects', 'users.id', '=', 'subjects.user_id')
            ->join('exams', 'exams.id', '=', 'subjects.exam_id')
            ->select('subjects.id', 'users.id as user_id', 'users.name as user_name', 'users.group as student_group',
                'subjects.obtained_points', 'subjects.submitted_at', 'subjects.forced_submit', 'subjects.penalizations',
                'subjects.time_promoted',
                DB::raw('TIMEDIFF(subjects.submitted_at, exams.ends_at) as time_diff'),
                DB::raw('TIMESTAMPDIFF(SECOND, exams.ends_at, subjects.submitted_at) as second_diff')) // negativ daca nu a intarziat, pozitiv altfel
            ->where('subjects.exam_id', $id)
            ->whereRaw('TIMESTAMPDIFF(SECOND, exams.ends_at, subjects.submitted_at) >= 180')
            ->orderBy('subjects.submitted_at', 'desc')
            ->paginate(50);
    }

    public function promoteByTimeStudent($examId, $userId)
    {
        DB::table('subjects')
            ->where('exam_id', $examId)
            ->where('user_id', $userId)
            ->update([
                'time_promoted' => 1
            ]);
    }

    public function undoPromoteByTimeStudent($examId, $userId)
    {
        DB::table('subjects')
            ->where('exam_id', $examId)
            ->where('user_id', $userId)
            ->update([
                'time_promoted' => 0
            ]);
    }

    private function getSubjectStatsBySearch($examId, $name) {
        return DB::table('users')
            ->join('subjects', 'users.id', '=', 'subjects.user_id')
            ->join('exams', 'exams.id', '=', 'subjects.exam_id')
            ->select('subjects.id', 'users.id as user_id', 'users.name as user_name', 'users.group as student_group',
                'subjects.obtained_points', 'subjects.submitted_at', 'subjects.forced_submit', 'subjects.penalizations',
                'subjects.time_promoted',
                DB::raw('TIMEDIFF(subjects.submitted_at, exams.ends_at) as time_diff'),
                DB::raw('TIMESTAMPDIFF(SECOND, exams.ends_at, subjects.submitted_at) as second_diff')) // negativ daca nu a intarziat, pozitiv altfel
            ->where('subjects.exam_id', $examId)
            ->where('users.name', 'like', '%'.$name.'%')
            ->orderBy('subjects.submitted_at', 'desc')
            ->paginate(50);
    }

    public function getExamStatsBySearch($examId, $name) {
        $examInformation = $this->getExamInfoForStudentStats($examId);

        $subjectInfo = $this->getSubjectStatsBySearch($examId, $name);

        return array(
            'exam' => $examInformation,
            'subject' => $subjectInfo
        );
    }
}
