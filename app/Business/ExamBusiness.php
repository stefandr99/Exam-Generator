<?php


namespace App\Business;

use App\Repository\Interfaces\IExamRepository;
use App\Service\ExamServices;
use DateInterval;
use DateTime;
use DateTimeZone;

class ExamBusiness
{
    private $examRepository;
    private $examService;

    public function __construct(IExamRepository $examRepository)
    {
        $this->examRepository = $examRepository;
        $this->examService = new ExamServices();
    }

    public function getExamInfo($examId){
        return $this->examRepository->getInfoById($examId);
    }

    public function checkStealExamStart($examInfo) {
        $examDate = new DateTime($examInfo->starts_at);
        $presentDate = new DateTime("now");
        $presentDate->add(new DateInterval("PT3H"));
        return $presentDate < $examDate;
    }

    public function getExamTime($examInfo) {
        $endDate = new DateTime($examInfo->ends_at);
        $presentDate = new DateTime("now");
        $presentDate->add(new DateInterval("PT3H"));
        $result = $endDate->diff($presentDate);

        $examHours = $result->h;
        $examMinutes = $result->i;
        $examSeconds = $result->s;

        return array($examHours, $examMinutes, $examSeconds);
    }

    public function getExamResult($examId, $userId) {
        return $this->examRepository->getResult($examId, $userId);
    }

    public function scheduleDB($exam) {
        $newExam = array();
        $newExam['courseId'] = $exam['exam_course'];
        $newExam['type'] = $exam['exam_type'];
        $newExam['startsAt'] = $exam['exam_date'];
        $newExam['endsAt'] = $this->getExamEndTime($exam['exam_date'], $exam['exam_hours'], $exam['exam_minutes']);
        $newExam['hours'] = $exam['exam_hours'];
        $newExam['minutes'] = $exam['exam_minutes'];
        $newExam['totalExercises'] = $exam['number_of_exercises'] + 1;

        $exercisesInfo = $this->examService->createDBExercisesJSON($exam);
        $newExam['exercises'] = json_encode($exercisesInfo[0]);
        $newExam['totalPoints'] = $exercisesInfo[1];
        $newExam['minimumPoints'] = $exam['exam_minimum'];
        $newExam['penalization'] = json_encode($this->examService->getExamPenalization($exam));

        $this->examRepository->create($newExam);
    }

    public function scheduleAny($exam) {
        $newExam = array();
        $newExam['courseId'] = $exam['exam_course'];
        $newExam['type'] = $exam['exam_type'];
        $newExam['startsAt'] = $exam['exam_date'];
        $newExam['endsAt'] = $this->getExamEndTime($exam['exam_date'], $exam['exam_hours'], $exam['exam_minutes']);
        $newExam['hours'] = $exam['exam_hours'];
        $newExam['minutes'] = $exam['exam_minutes'];
        $newExam['totalExercises'] = $exam['number_of_exercises'] + 1;

        $exercisesInfo = $this->examService->createExercisesJSON($exam);
        $newExam['exercises'] = json_encode($exercisesInfo[0]);
        $newExam['totalPoints'] = $exercisesInfo[1];
        $newExam['minimumPoints'] = $exam['exam_minimum'];
        $newExam['penalization'] = json_encode($this->examService->getExamPenalization($exam));

        $this->examRepository->create($newExam);
    }

    public function getExamEndTime($start, $hours, $minutes) {
        $endTime = new DateTime($start);
        $duration = 'PT' . strval($hours) . 'H' . strval($minutes) . 'M';
        $endTime->add(new DateInterval($duration));

        return $endTime;
    }

    public function getExams($userId, $userRole, $yearAndSem)
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

    private function getExamsForTeacher($userId, $yearAndSemester)
    {
        $exams = $this->examRepository->getAllForTeachers($userId, $yearAndSemester->semester);

        $examsInformation = array(2, $exams);
        return $examsInformation;
    }

    private function getExamsForStudents($userId, $yearAndSemester)
    {
        $exams = $this->examRepository->getAllForStudents($userId, $yearAndSemester->year, $yearAndSemester->semester);

        $teachers = $this->getExamTeachers($exams);

        $examsInformation = array(3, $exams, $teachers);
        return $examsInformation;
    }

    public function getExamTeachers($exams)
    {
        $teachers = array();
        foreach ($exams as $exam) {
            $teachers[$exam->exam_id] = $this->examRepository->getTeachersByExam($exam->exam_id);
        }

        return $teachers;
    }

    public function getExamById($examId)
    {
        return $this->examRepository->getExamById($examId);
    }

    public function update($exam) {
        $updatedExam = array();
        $updatedExam['id'] = $exam['exam_id'];
        $updatedExam['type'] = $exam['exam_type'];
        $updatedExam['starts_at'] = $exam['exam_date'];
        $updatedExam['ends_at'] = $this->getExamEndTime($exam['exam_date'], $exam['exam_hours'], $exam['exam_minutes']);
        $updatedExam['hours'] = $exam['exam_hours'];
        $updatedExam['minutes'] = $exam['exam_minutes'];
        $updatedExam['number_of_exercises'] = $exam['number_of_exercises'] + 1;

        if($exam['exam_course'] == 'any')
            $exercisesInfo = $this->examService->createExercisesJSON($exam);
        else
            $exercisesInfo = $this->examService->createDBExercisesJSON($exam);
        $updatedExam['exercises'] = json_encode($exercisesInfo[0]);
        $updatedExam['total_points'] = $exercisesInfo[1];
        $updatedExam['minimum_points'] = $exam['exam_minimum'];
        $updatedExam['penalization'] = json_encode($this->examService->getExamPenalization($exam));

        $this->examRepository->update($updatedExam);
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

    public function getFilteredExamStatsBySearch($examId, $name) {
        $info = $this->examRepository->getExamStatsBySearch($examId, $name);

        return $info;
    }

    public function history($id) {
        $exams = $this->examRepository->history($id);

        return $exams;
    }
}
