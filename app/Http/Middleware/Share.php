<?php

namespace App\Http\Middleware;
use App\Library as LibraryModel;
use Illuminate\Support\Facades\Auth;

use Closure;

class Share
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
            'library_access' => Auth::id(),
        ])
        ->count();
        
        if($access == 1):
            return $next($request);
        endif;
    }
}
