<?php

namespace App\Http\Controllers;

use App\Business\DidacticBusiness;
use App\Repository\Interfaces\IDidacticRepository;
use Illuminate\Http\Request;

class DidacticController extends Controller
{
    private DidacticBusiness $didacticBusiness;

    public function __construct(IDidacticRepository $didacticRepository) {
        $this->didacticBusiness = new DidacticBusiness($didacticRepository);
    }

    public function addTeacherToCourse(Request $request) {
        if($request->teacherToAdd != 0)
            $this->didacticBusiness->addTeacher($request->teacherToAdd, $request->courseId);

        return redirect()->route('show_courses');
    }

    public function deleteTeacherFromCourse(Request $request) {
        $teacherId = $request->teacherToDelete;
        $courseId = $request->courseId;
        if($request->teacherToDelete != 0) {
            $this->didacticBusiness->deleteTeacher($teacherId, $courseId);
        }

        return redirect()->route('show_courses');
    }
}
