<?php

namespace App\Http\Controllers;

use App\Business\ExamBusiness;
use http\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ExamController extends Controller
{
    private $examBusiness;
    private $exercise1, $exercise2, $exercise3, $exercise4;

    public function __construct()
    {
        $this->middleware('auth');
        $this->examBusiness = new ExamBusiness();
    }

    public function generate() {
        $this->exercise1 = $this->examBusiness->generateFirst();
        $this->exercise2 = $this->examBusiness->generateSecond();
        $this->exercise3 = $this->examBusiness->generateThird();
        $this->exercise4 = $this->examBusiness->generateFourth();
        Session::put('exercises', array($this->exercise1, $this->exercise2, $this->exercise3, $this->exercise4));
        return view('exam', ['exercise1' => $this->exercise1, 'exercise2' => $this->exercise2,
            'exercise3' => $this->exercise3, 'exercise4' => $this->exercise4]);
    }

    public function correctPartial(Request $request) {

        if($request->ajax()) {
            $answers = $request->input('arg');
            $answers = json_decode($answers);
            $data = $this->examBusiness->correct($answers);
            $correctedPartial = $data['correctedPartial'];
            $points = $data['points'];
            return $points;
        }
        else
            return "ERROR!";
    }
}
