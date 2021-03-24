<?php

namespace App\Http\Controllers;

use App\Business\CourseBusiness;
use App\Business\DidacticBusiness;
use App\Business\UserBusiness;
use App\Repository\Interfaces\ICourseRepository;
use App\Repository\Interfaces\IDidacticRepository;
use App\Repository\Interfaces\IUserRepository;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    private UserBusiness $userBusiness;
    private CourseBusiness $courseBusiness;
    private DidacticBusiness $didacticBusiness;

    public function __construct(IUserRepository $userRepository, ICourseRepository $courseRepository,
                                IDidacticRepository $didacticRepository)
    {
        $this->middleware('auth');
        $this->userBusiness = new UserBusiness($userRepository);
        $this->courseBusiness = new CourseBusiness($courseRepository);
        $this->didacticBusiness = new DidacticBusiness($didacticRepository);
    }

    public function prepareNewCourse() {
        $teachers = $this->userBusiness->getTeachers();

        return view('course/prepare', ['teachers' => $teachers]);
    }

    public function addNewCourse(Request $request) {
        if ($request->ajax()) {
            $name = $request->input('name');
            $teachers = json_decode($request->input('teachers'), true);
            $year = $request->input('year');
            $semester = $request->input('semester');
            $credits = $request->input('credits');
            $course = array(
                'name' => $name,
                'year' => $year,
                'semester' => $semester,
                'credits' => $credits
            );
            $this->courseBusiness->addCourse($course);

            $courseId = $this->courseBusiness->getIdByName($name);
            $this->didacticBusiness->addTeachersToCourse($teachers, $courseId);
        }
    }

    public function showCourses() {
        $courses = $this->courseBusiness->all();
        $teachersAndNoTeachers = $this->courseBusiness->getTeachersAndNoTeachersByCourses($courses);

        $teachers = $teachersAndNoTeachers['teachers'];
        $noTeachers = $teachersAndNoTeachers['noTeachers'];

        return view('course/showAll', [
            'courses' => $courses,
            'teachers' => $teachers,
            'noTeachers' => $noTeachers
        ]);
    }

    public function search(Request $request) {
        $coursesAndTeachers = $this->courseBusiness->search($request->name);

        $courses = $coursesAndTeachers['courses'];
        $teachers = $coursesAndTeachers['teachers'];
        $noTeachers = $coursesAndTeachers['noTeachers'];

        return view('course/showAll', [
            'courses' => $courses,
            'teachers' => $teachers,
            'noTeachers' => $noTeachers
        ]);
    }

}
