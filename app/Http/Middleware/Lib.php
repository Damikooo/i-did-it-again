<?php

namespace App\Http\Middleware;

use Closure;
use App\Library as LibraryModel;
use Illuminate\Support\Facades\Auth;
class Lib
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
        $access = LibraryModel::where('library_access', request()->segment(2))
        ->orWhere('user_id', Auth::id())
        ->get()
        ->first();
        if (!empty($access) or request()->segment(2) == Auth::id()):
            return $next($request);
        endif;
        return redirect('/home');
    }
}
