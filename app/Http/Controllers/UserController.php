<?php

namespace App\Http\Controllers;

use App\Business\UserBusiness;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public $userBusiness;
    public function __construct()
    {
        $this->middleware('auth');
        $this->userBusiness = new UserBusiness();
    }

    public function showAll() {
        $users = $this->userBusiness->getAll();
        return view('user/showUsers', ['users' => $users]);
    }

    public function updateUserRole($id, $newRole) {
        $this->userBusiness->changeRole($id, $newRole);
        return redirect()->route('users');
    }

    public function search(Request $request) {
        $users = $this->userBusiness->search($request->name);

        return view('user/showUsers', ['users' => $users]);
    }
}
