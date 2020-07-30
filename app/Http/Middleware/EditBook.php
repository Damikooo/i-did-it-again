<?php

namespace App\Http\Middleware;
use App\Book as BookModel;
use Illuminate\Support\Facades\Auth;
use Closure;

class EditBook
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
        $access = BookModel::where('author', Auth::id())
        ->find(1);

        if (!empty($access)):
            return $next($request);
        endif;
        return redirect('/home');
    }
}
