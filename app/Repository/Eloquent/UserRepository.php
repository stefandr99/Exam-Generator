<?php


namespace App\Repository\Eloquent;


use App\Repository\Interfaces\IUserRepository;
use Illuminate\Support\Facades\DB;

class UserRepository implements IUserRepository
{

    public function all(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return DB::table('users')
            ->where('year', '<', 4)
            ->orWhereNull('year')
            ->orderBy("role")
            ->orderBy("year")
            ->paginate(50);
    }

    public function getRoleById($id)
    {
        return DB::table('users')
            ->select('role')
            ->where('id', $id)
            ->get()
            ->first();
    }

    public function getYearAndSemesterById($id)
    {
        return DB::table('users')
            ->select('year', 'semester')
            ->where('id', $id)
            ->get()
            ->first();
    }

    public function getNameById($id)
    {
        return DB::table('users')
            ->select('name')
            ->where('id', $id)
            ->get()
            ->first();
    }

    public function getTeachers(): \Illuminate\Support\Collection
    {
        return DB::table('users')
            ->where('role', '=', '2')
            ->get();
    }

    public function getIdByName($name)
    {
        return DB::table('users')
            ->select('id')
            ->where('name', $name)
            ->get()
            ->first();
    }

    public function passToNextSemester()
    {
        DB::table('users')
            ->where('semester', '=', 1)
            ->update([
                'semester' => 2
            ]);
    }

    public function passToNextYear()
    {
        DB::table('users')
            ->where('semester', '=', 2)
            ->update([
                'year' => DB::raw('year + 1'),
                'semester' => 1
            ]);
    }

    public function changeRole($id, $newRole)
    {
        DB::table('users')
            ->where('id', $id)
            ->update(['role' => $newRole]);
    }

    public function delete($id)
    {
        DB::table('users')
            ->where('id', $id)
            ->delete();
    }

    public function search($toMatch, $criteria): \Illuminate\Support\Collection
    {
        switch ($criteria) {
            case 'name':
                $users = DB::table('users')
                    ->where('name', 'like', '%'.$toMatch.'%')
                    ->orderBy("role")
                    ->get();
                break;
            case 'registration_number':
                $users = DB::table('users')
                    ->where('registration_number', '=', $toMatch)
                    ->orderBy("role")
                    ->get();
                break;
            case 'year&group':
                $info = explode(" ", $toMatch);
                $users = DB::table('users')
                    ->where('year', '=', $info[0])
                    ->where('group', '=', $info[1])
                    ->orderBy("role")
                    ->get();
                break;
            case 'year':
                $users = DB::table('users')
                    ->where('year', '=', $toMatch)
                    ->orderBy("role")
                    ->get();
                break;
            case 'group':
                $users = DB::table('users')
                    ->where('group', '=', $toMatch)
                    ->orderBy("role")
                    ->get();
                break;
        }


        return $users;
    }
}
