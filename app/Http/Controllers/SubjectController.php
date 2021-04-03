<?php

namespace App\Http\Controllers;

use App\Business\ExamBusiness;
use App\Business\SubjectBusiness;
use App\Repository\Interfaces\IExamRepository;
use App\Repository\Interfaces\ISubjectRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubjectController extends Controller
{
    private $subjectBusiness;
    private $examBusiness;

    public function __construct(ISubjectRepository $subjectRepository,
                                IExamRepository $examRepository)
    {
        $this->middleware('auth');
        $this->subjectBusiness = new SubjectBusiness($subjectRepository);
        $this->examBusiness = new ExamBusiness($examRepository);
    }


    public function generate($id) {
        $examInfo = $this->examBusiness->getExamInfo($id);
        $penalization = json_decode($examInfo[0]->penalization, true);

        $userId = Auth::id();

        if($this->examBusiness->checkStealExamStart($examInfo[0]))
            return redirect()->route('steal_start_exam', array('examId' => $id, 'userId' => $userId));

        $exercises = $this->subjectBusiness->generate($id, $examInfo);
        $optionsNumber = array();
        for($index = 0; $index < count($exercises[0]); $index++) {
            $optionsNumber[$index] = $exercises[0][$index]['exercise']['options']['counter'];
        }

        $examTime = $this->examBusiness->getExamTime($examInfo[0]);
        session(['userPenalty' => 0]);

        return view('exam/exam', ['exercises' => $exercises[0], 'info' => $examInfo[0],
            'optionsNumber' => $optionsNumber, 'examId' => $id,
            'examTime' => $examTime, 'penalization' => $penalization]);
    }

    public function increasePenalty() {
        $currentPenalty = session('userPenalty');
        session(['userPenalty' => $currentPenalty + 1]);
    }

    public function correctExam(Request $request) {

        if($request->ajax()) {
            $studentAnswers = $request->input('answers');
            $exercisesNumber = $request->input('exercisesNr');
            $optionsNumber = $request->input('optionsNr');
            $examId = $request->input('examId');
            $forcedSubmit = $request->input('isForced');

            $studentAnswers = json_decode($studentAnswers);
            $subjectInfo = $this->subjectBusiness->correct($studentAnswers, $exercisesNumber, $optionsNumber, $examId, $forcedSubmit);
            $subjectInfo = json_encode($subjectInfo);
            return $subjectInfo;
        }
        else
            return 0;
    }
}
