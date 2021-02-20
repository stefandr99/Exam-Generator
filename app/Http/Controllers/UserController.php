<?php

namespace App\Http\Controllers;

use App\Business\UserBusiness;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public $uBusiness;
    public function __construct()
    {
        $this->middleware('auth');
        $this->uBusiness = new UserBusiness();
    }

    public function showAll() {
        return view('user/showUsers', ['users' => $this->uBusiness->getAll()]);
    }

    public function updateUserRole($id, $newRole) {
        $this->uBusiness->changeRole($id, $newRole);
        return redirect()->route('users');
    }
}
