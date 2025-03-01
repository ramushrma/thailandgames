<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUserSession
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->session()->has('authuser')) {
            $request->session()->flash('msg', 'Please log in first to access the dashboard.');
            $request->session()->flash('msg_class', 'danger'); // Alert ke liye class
            return redirect()->route('login');
        }
        return $next($request);
    }
}
