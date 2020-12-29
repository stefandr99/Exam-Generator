<?php

namespace App\Http\Controllers;

use App\Business\ExamBusiness;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    private $examBusiness;
    public function __construct()
    {
        $this->middleware('auth');
        $this->examBusiness = new ExamBusiness();
    }

    public function generate() {
        $exercise1 = $this->examBusiness->generateFirst();
        $exercise2 = $this->examBusiness->generateSecond();
        $exercise3 = $this->examBusiness->generateThird();
        $exercise4 = $this->examBusiness->generateFourth();
        return view('exam', ['exercise1' => $exercise1, 'exercise2' => $exercise2,
            'exercise3' => $exercise3, 'exercise4' => $exercise4]);
    }
}
