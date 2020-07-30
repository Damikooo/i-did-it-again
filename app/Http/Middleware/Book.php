<?php

namespace App\Http\Middleware;

use Closure;
use App\Book as BookModel;
use Illuminate\Support\Facades\Auth;

class Book
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
        $access = BookModel::where([
            'id' => request()->segment(2),
            'access' => 1,
        ])
        ->count();

        if($access == 0):
            return redirect('/home');
        else:
            return $next($request);
        endif;
    }
}
