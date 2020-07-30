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
        $access = LibraryModel::where([
            'user_id' => Auth::id()
        ])
        ->count();
        
        if($access == 1):
            return $next($request);
        endif;
    }
}
