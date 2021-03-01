<?php

namespace App\Http\Controllers;

use App\Business\Business;
use App\Business\UserBusiness;
use App\User;
use Illuminate\Http\Request;

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
}
