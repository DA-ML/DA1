<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckLogin
{
    public function handle(Request $request, Closure $next)
    {
        // Kiểm tra xem người dùng đã đăng nhập chưa
        if (!Session::has('user')) {
            // Nếu chưa đăng nhập, chuyển hướng đến trang login
            return redirect()->route('login');
        }

        return $next($request);
    }
}
