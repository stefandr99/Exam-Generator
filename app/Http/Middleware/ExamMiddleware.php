<?php

namespace App\Http\Middleware;

use App\Exam;
use Closure;
use DateInterval;
use DateTime;
use Illuminate\Support\Facades\DB;

class ExamMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $examId = $request->id;
        $exam = Exam::find($examId);
        $presentDate = new DateTime("now");
        $presentDate->add(new DateInterval("PT3H"));
        $start = new DateTime($exam->starts_at);
        $end = new DateTime($exam->ends_at);

        if($presentDate < $start || $presentDate > $end) {
            return redirect('/');
        }

        $course = DB::table('exams')
            ->join('courses', 'courses.id', '=', 'exams.course_id')
            ->select('courses.*')
            ->where('exams.id', $examId)
            ->get()
            ->first();
        if($course->year != auth()->user()->year || $course->semester != auth()->user()->semester) {
            return redirect('/');
        }

        return $next($request);
    }
}
