<?php

namespace App\Http\Middleware;

use App\Library as LibraryModel;
use Illuminate\Support\Facades\Auth;
use Closure;
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
        $access = LibraryModel::where([
            'user_id' => Auth::id(),
            'library_access' => request()->segment(2),
        ])
        ->count();
        if($access == 1 or request()->segment(2) == Auth::id()):
            return $next($request);
        else:
            return redirect('/home');
        endif;
    }
}
