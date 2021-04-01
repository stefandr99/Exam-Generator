<?php


namespace App\Business;

use App\Repository\Interfaces\IExamRepository;
use DateInterval;
use DateTime;
use DateTimeZone;

class ExamBusiness
{
    private $examRepository;

    public function __construct(IExamRepository $examRepository)
    {
        $this->examRepository = $examRepository;
    }

    public function getExamInfo($examId)
    {
        return $this->examRepository->getInfoById($examId);
    }

    public function checkStealExamStart($examInfo) {
        $examDate = new DateTime($examInfo->starts_at);
        $presentDate = new DateTime("now", new DateTimeZone('UTC'));
        $presentDate->add(new DateInterval('PT2H'));
        return $presentDate < $examDate;
    }

    public function getExamTime($examInfo) {
        $examDate = new DateTime($examInfo->starts_at);
        $presentDate = new DateTime("now", new DateTimeZone('UTC'));
        $presentDate->add(new DateInterval('PT2H'));

        $examHours = intval($examDate->format('H')) + $examInfo->hours - intval($presentDate->format('H'));
        $examMinutes = intval($examDate->format('i')) + $examInfo->minutes - intval($presentDate->format('i'));
        $examSeconds = intval($examDate->format('s')) - intval($presentDate->format('s'));

        return (3600 * $examHours + 60 * $examMinutes + $examSeconds);
    }

    public function getExamResult($examId, $userId) {
        return $this->examRepository->getResult($examId, $userId);
    }

    public function schedule($info, $exercises, $penalization) {
        $courseId = $info[0];
        $endTime = $this->getExamEndTime($info[2], $info[3], $info[4]);

        $exam = array(
            'id' => $courseId,
            'type' => $info[1],
            'starts_at' => $info[2],
            'ends_at' => $endTime,
            'hours' => $info[3],
            'minutes' => $info[4],
            'number_of_exercises' => $exercises[0],
            'exercises_type' => json_encode($exercises[1]),
            'total_points' => $exercises[2],
            'minimum_points' => $info[5],
            'penalization' => json_encode($penalization)
        );
        $this->examRepository->create($exam);
    }

    public function getExamEndTime($start, $hours, $minutes) {
        $endTime = new DateTime($start);
        $duration = 'PT' . strval($hours) . 'H' . strval($minutes) . 'M';
        $endTime->add(new DateInterval($duration));

        return $endTime;
    }

    public function getExams($userId, $userRole, $yearAndSem): array
    {
        if ($userRole->role == 2)
            return $this->getExamsForTeacher($userId);
        else {
            return $this->getExamsForStudents($userId, $yearAndSem);
        }
    }

    private function getExamsForTeacher($userId): array
    {
        $exams = $this->examRepository->getAllForTeachers($userId);

        $examsInformation = array(2, $exams);
        return $examsInformation;
    }

    private function getExamsForStudents($userId, $yearAndSemester): array
    {
        $exams = $this->examRepository->getAllForStudents($userId, $yearAndSemester->year, $yearAndSemester->semester);

        $teachers = $this->getExamTeachers($exams);

        $examsInformation = array(3, $exams, $teachers);
        return $examsInformation;
    }

    public function getExamTeachers($exams): array
    {
        $teachers = array();
        foreach ($exams as $exam) {
            $teachers[$exam->exam_id] = $this->examRepository->getTeachersByExam($exam->exam_id);
        }

        return $teachers;
    }

    public function getExamById($examId): \Illuminate\Support\Collection
    {
        return $this->examRepository->getExamById($examId);
    }

    public function updateExam($id, $info, $exercises, $course) {
        $endTime = $this->getExamEndTime($info[2], $info[3], $info[4]);

        $exam = array(
            'id' => $id,
            'course_id' => $course->id,
            'type' => $info[1],
            'starts_at' => $info[2],
            'ends_at' => $endTime,
            'hours' => $info[3],
            'minutes' => $info[4],
            'number_of_exercises' => $exercises[0],
            'exercises_type' => json_encode($exercises[1]),
            'total_points' => $exercises[2],
            'minimum_points' => $info[5]
        );

        $this->examRepository->update($exam);
    }
}
