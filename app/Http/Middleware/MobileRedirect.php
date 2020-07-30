<?php

namespace App\Http\Middleware;

use Closure;
use App\Book;

class MobileRedirect
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($id)
    {
        $share = Book::where([
            'access' => 1,
            'id' => $id
            ])
            ->get();
            dd($share);
        if ($share == 0):
            return redirect('/');
        else:
            $book = Book::where('id', $id)
            ->get();
            return view('book');
            // View::make('book')->render();
            // return view('book');
        endif;
    }
}
