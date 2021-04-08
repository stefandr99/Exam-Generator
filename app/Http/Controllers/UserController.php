<?php

namespace App\Http\Controllers;
use App\Business\CourseBusiness;
use App\Business\DidacticBusiness;
use App\Business\UserBusiness;
use App\Repository\Interfaces\ICourseRepository;
use App\Repository\Interfaces\IDidacticRepository;
use App\Repository\Interfaces\IUserRepository;
use App\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use SimpleXLSX;

class UserController extends Controller
{
    private $courseBusiness;
    private $userBusiness;
    private $didacticBusiness;

    public function __construct(IUserRepository $userRepository, ICourseRepository $courseRepository,
                                IDidacticRepository $didacticRepository)
    {
        $this->middleware('auth');
        $this->userBusiness = new UserBusiness($userRepository);
        $this->courseBusiness = new CourseBusiness($courseRepository);
        $this->didacticBusiness = new DidacticBusiness($didacticRepository);
    }

    public function showAll() {
        $users = $this->userBusiness->getAll();
        $courses = $this->courseBusiness->getAll();

        return view('user/showUsers', ['users' => $users, 'courses' => $courses]);
    }

    public function updateUserRole(Request $request, $id, $newRole) {
        $this->userBusiness->changeRole($id, $newRole);
        if($newRole == 2) {
            $courseId = $request->courseId;
            $this->didacticBusiness->addTeacher($id, $courseId);
        }

        return redirect()->route('users');
    }

    public function search(Request $request) {
        $search = $request->search;
        $criteria = $request->criteria;
        $users = $this->userBusiness->search($search, $criteria);
        $courses = $this->courseBusiness->getAll();

        return view('user/showUsers', ['users' => $users, 'courses' => $courses]);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'registration_number' => ['string', 'min:18', 'max:18', 'nullable'],
            'year' => ['integer', 'min:1', 'max:3', 'nullable'],
            'group' => ['string', 'min:2', 'max:2', 'nullable'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    public function registerByAdmin(Request $request) {
        $this->validator($request->all())->validate();
        $data = $request->all();
        User::create([
            'name' => $data['name'],
            'registration_number' => $data['registration_number'],
            'year' => $data['year'],
            'group' => $data['group'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        return redirect()->route('users');
    }

    public function registerBulk(Request $request) {
        $file_name = $_FILES['user-upload']['name'];
        $file_tmp =$_FILES['user-upload']['tmp_name'];

        move_uploaded_file($file_tmp,"./resources/users/".$file_name);
        if ( $xlsx = SimpleXLSX::parse("./resources/users/".$file_name) ) {
            for($i = 1; $i < count($xlsx->rows()); $i++) {
                $user = new User;
                $user->name = $xlsx->rows()[$i][0];
                $user->registration_number = $xlsx->rows()[$i][1];
                $user->year = $xlsx->rows()[$i][2];
                $user->group = $xlsx->rows()[$i][3];
                $user->semester = $xlsx->rows()[$i][4];
                $user->email = $xlsx->rows()[$i][5];
                $user->password = Hash::make($xlsx->rows()[$i][6]);
                $user->save();
            }
        } else {
            echo SimpleXLSX::parseError();
        }
        return redirect()->route('users');
    }

    public function passToNextSemester($semester) {
        if($semester == 1)
            $this->userBusiness->passToNextSemester();
        else
            $this->userBusiness->passToNextYear();

        return redirect()->route('users');
    }

    public function deleteUser(Request $request) {
        $id = $request->userId;
        $this->userBusiness->delete($id);

        return redirect()->route('users');
    }
}
