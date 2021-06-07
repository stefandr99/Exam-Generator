<?php

namespace App\Http\Middleware;

use Closure;

class ExamResultsMiddleware
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
        $userRequest = $request->userId;
        $userId = auth()->id();
        $role = auth()->user()->role;

        if($userRequest != $userId && $role != 2) {
            return redirect('/');
        }
        return $next($request);
    }
}
