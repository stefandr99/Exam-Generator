<?php

namespace App\Business;

use App\User;
use Illuminate\Support\Facades\DB;

class UserBusiness
{
    public function getAll() {
        return DB::table('users')
            ->where('year', '<', 4)
            ->orWhereNull('year')
            ->orderBy("role")
            ->orderBy("year")
            ->paginate(50);
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

    public function search($search, $criteria): \Illuminate\Support\Collection
    {
        switch ($criteria) {
            case 'name':
                $users = DB::table('users')
                    ->where('name', 'like', '%'.$search.'%')
                    ->orderBy("role")
                    ->get();
                break;
            case 'registration_number':
                $users = DB::table('users')
                    ->where('registration_number', '=', $search)
                    ->orderBy("role")
                    ->get();
                break;
            case 'year&group':
                $info = explode(" ", $search);
                $users = DB::table('users')
                    ->where('year', '=', $info[0])
                    ->where('group', '=', $info[1])
                    ->orderBy("role")
                    ->get();
                break;
            case 'year':
                $users = DB::table('users')
                    ->where('year', '=', $search)
                    ->orderBy("role")
                    ->get();
                break;
            case 'group':
                $users = DB::table('users')
                    ->where('group', '=', $search)
                    ->orderBy("role")
                    ->get();
                break;
        }


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
        $teachers = DB::select(DB::raw('select users.id, name from users
                                    where users.role = 2 and users.id not in
                                     (select DISTINCT d.teacher_id from didactics as d where d.course_id = ' . $courseId. ')'));

        return $teachers;
    }

    public function passToNextSemester() {
        DB::table('users')
            ->where('semester', '=', 1)
            ->update([
                'semester' => 2
            ]);
    }

    public function passToNextYear() {
        DB::table('users')
            ->where('semester', '=', 2)
            ->update([
                'year' => DB::raw('year + 1'),
                'semester' => 1
            ]);
    }

    public function deleteUserById($userId) {
        DB::table('users')
            ->where('id', $userId)
            ->delete();

    }
}
