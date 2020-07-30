<?php

namespace App\Http\Middleware;

use Closure;
use App\Book as BookModel;
use App\Library as LibraryModel;
use Illuminate\Support\Facades\Auth;

class Library
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
        $lib_id = request()->segment(2);

        $access = LibraryModel::where([
            'user_id' => $lib_id,
            'library_access' => Auth::id(),
        ])
        ->count();

        if(Auth::id() == null):
            return redirect('/home');
        else:
            return $next($request);
        endif;
    }
}
