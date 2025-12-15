<?php
namespace App\http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckDummyLogin
{
    public function handle(Request $request, Closure $next)
    {
        // Jika TIDAK ada session 'user'
        if (!Session::has('user')) {
            // Lempar ke halaman login
            return redirect()->route('login');
        }
        // Jika ada, lanjutkan
        return $next($request);
    }
}