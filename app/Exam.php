<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'course_id', 'teacher_id', 'type', 'date', 'hours', 'minutes', 'number_of_exercises', 'exercises_type', 'total_points'
    ];
}
