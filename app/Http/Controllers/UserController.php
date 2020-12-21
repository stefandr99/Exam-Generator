<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public $user;
    public function __construct()
    {
        $this->middleware('auth');
        $this->user = new User();
    }

    public function showAll() {
        return view('showUsers', ['users' => $this->user->getAll()]);
    }
}
