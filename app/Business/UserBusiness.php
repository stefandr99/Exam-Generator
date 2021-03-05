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

    public function getRole($id): \Illuminate\Support\Collection
    {
        $userId = DB::table('users')
            ->select('role')
            ->where('id', $id)
            ->get();

        return $userId;
    }

    public function getYearAndSemester($userId): \Illuminate\Support\Collection
    {
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

    public function search($name): \Illuminate\Support\Collection
    {
        $users = DB::table('users')
            ->where('name', 'like', '%'.$name.'%')
            ->get();

        return $users;
    }

    public function getAllTeachers(): \Illuminate\Support\Collection
    {
        $teachers = DB::table('users')
            ->where('role', '=', '2')
            ->get();

        return $teachers;
    }

    public function getIdByName($name) {
        $id = DB::table('users')
            ->select('id')
            ->where('name', $name)
            ->get()
            ->first();

        return $id;
    }

    public function getTeachersFromCourseId($courseId) {
        $teachers = DB::table('users as u')
            ->join('didactics as d', 'u.id', '=', 'd.teacher_id')
            ->where('d.course_id', $courseId)
            ->select('u.id', 'name')
            ->get();

        return $teachers;
    }

    public function getNoTeachersFromCourseId($courseId) {
        /*$teachers = DB::table('users as u')
            ->where('u.role', '=', 2)
            ->whereNotIn('u.id', function($query) use ($courseId) {
                $query->select('d.teacher_id')
                    ->distinct()
                    ->from('didactics as d')
                    ->where('d.course_id', $courseId);
            })
            ->select('u.id', 'name')
            ->get();*/

        $teachers = DB::select(DB::raw('select users.id, name from users
                                    where users.role = 2 and users.id not in
                                     (select DISTINCT d.teacher_id from didactics as d where d.course_id = ' . $courseId. ')'));

        return $teachers;
    }
}
