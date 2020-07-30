<?php

namespace App\Http\Middleware;

use Closure;
use App\Book as BookModel;
use App\Library as LibraryModel;
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
            $access = BookModel::where(['id' => request()->segment(2)])
            ->get()
            ->first();
            
            if (!empty($access)):
                if($access->access == 1 or $access->author == Auth::id()):
                    return $next($request);
                endif;
            endif;
            return redirect('/home');
    }
}
