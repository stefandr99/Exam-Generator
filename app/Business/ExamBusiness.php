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
        $presentDate = new DateTime("now");
        $presentDate->add(new DateInterval('PT3H'));
        return $presentDate < $examDate;
    }

    public function getExamTime($examInfo) {
        $examDate = new DateTime($examInfo->starts_at);
        $presentDate = new DateTime("now");
        $presentDate->add(new DateInterval('PT3H'));

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
            return $this->getExamsForTeacher($userId, $yearAndSem);
        else {
            return $this->getExamsForStudents($userId, $yearAndSem);
        }
    }

    public function getLast30DaysExams($userId, $yearAndSem) {
        return $this->examRepository->getlast30DaysExamsForTeacher($userId, $yearAndSem->semester);
    }

    private function getExamsForTeacher($userId, $yearAndSemester): array
    {
        $exams = $this->examRepository->getAllForTeachers($userId, $yearAndSemester->semester);

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

    public function getExamStats($examId) {
        $info = $this->examRepository->getExamStats($examId);

        return $info;
    }

    public function getFilteredExamStats($examId, $filter) {
        $info = $this->examRepository->getFilteredExamStats($examId, $filter);

        return $info;
    }

    /*
    private function manageSubmitTime($endTime, $studentTimeInfo) {
        $endTime = new DateTime($endTime);
        $endHour = $endTime->format('H:i:s');

        $timeInfo = array();
        foreach ($studentTimeInfo as $subjectTimeDetails) {
            $submitTime = new DateTime($subjectTimeDetails->submitted_at);
            $submitHour = $submitTime->format('H:i:s');
            $difference = $submitTime->diff($endTime); // aici va fi invert = 1 daca submit > end
            $difference = array(
                'hours' => $difference->h,
                'minutes' => $difference->i,
                'seconds' => $difference->s,
                'invert' => $difference->invert
            );
            $timeInfo[$subjectTimeDetails->id] = array(
                'endHour' => $endHour,
                'submitHour' => $submitHour,
                'timeDifference' => $difference
            );
        }

        return $timeInfo;
    }*/

    public function promoteStudent($exam, $user) {
        $this->examRepository->promoteByTimeStudent($exam, $user);
    }

    public function undoPromoteStudent($exam, $user) {
        $this->examRepository->undoPromoteByTimeStudent($exam, $user);
    }
}
