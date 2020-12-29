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
}
