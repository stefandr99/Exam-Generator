<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = [
        'course_id', 'teacher_id', 'type', 'date', 'hours', 'minutes', 'number_of_exercises', 'exercises_type', 'total_points'
    ];
}
