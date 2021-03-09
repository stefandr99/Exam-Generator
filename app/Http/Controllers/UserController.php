<?php

namespace App\Http\Controllers;
use App\Business\Business;
use App\Business\UserBusiness;
use App\User;
use Illuminate\Http\Request;

use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use SimpleXLSX;

class UserController extends Controller
{
    public $business;
    public function __construct()
    {
        $this->middleware('auth');
        $this->business = new Business();
    }

    public function showAll() {
        $users = $this->business->user->getAll();

        return view('user/showUsers', ['users' => $users]);
    }

    public function updateUserRole($id, $newRole) {
        $this->business->user->changeRole($id, $newRole);

        return redirect()->route('users');
    }

    public function search(Request $request) {
        $users = $this->business->user->search($request->name);

        return view('user/showUsers', ['users' => $users]);
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
}
