<?php

namespace App\Business;

use App\User;
use Illuminate\Support\Facades\DB;

class UserBusiness
{
    public function getAll() {
        return User::all()->sortBy("role");
    }

    public function changeRole($id, $newRole) {
        DB::table('users')
            ->where('id', $id)
            ->update(['role' => $newRole]);
    }

    public function getRole($id) {
        $userId = DB::table('users')
            ->select('role')
            ->where('id', $id)
            ->get();

        return $userId;
    }

    public function getYearAndSemester($userId) {
        $yearAndSem = DB::table('users')
            ->select('year', 'semester')
            ->where('id', $userId)
            ->get();

        return $yearAndSem;
    }

    public function getName($userId) {
        $name = DB::table('users')
            ->select('name')
            ->where('id', $userId)
            ->get()
            ->first();

        return $name;
    }

    public function search($name) {
        $users = DB::table('users')
            ->where('name', 'like', '%'.$name.'%')
            ->get();

        return $users;
    }
}
