<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SinhVien;
use App\Models\GiaoVien;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Controller
{
    // Hiển thị trang đăng nhập
    public function showLoginForm()
    {
        return view('login');
    }

    // Xử lý đăng nhập
    public function login(Request $request)
    {
        // Validate input
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Kiểm tra đăng nhập cho sinh viên
        $sinhvien = SinhVien::where('mssv', $request->username)->first();
        if ($sinhvien && $sinhvien->password_sv == $request->password) {
            // Lưu thông tin sinh viên vào session
            Session::put('user', [
                'role' => 'sinhvien',
                'name' => $sinhvien->tensv,
                'id' => $sinhvien->mssv,
                'email' => $sinhvien->emailsv,
                'date' => $sinhvien->ngaysinh,
                'system' => $sinhvien->hedaotao,
                'password_sv' => $sinhvien->password_sv,
            ]);
            return redirect()->route('student.dashboard');
        }

        // Kiểm tra đăng nhập cho giáo viên
        $giaovien = GiaoVien::where('msgv', $request->username)->first();
        if ($giaovien && $giaovien->password_gv == $request->password) {
            // Lưu thông tin giáo viên vào session
            Session::put('user', [
                'role' => 'giaovien',
                'name' => $giaovien->tengv,
                'id' => $giaovien->msgv,
                'email' => $giaovien->emailgv,
                'date' => $giaovien->ngaysinh,
                'major' => $giaovien->khoa,
                'password_gv' => $giaovien->password_gv,
            ]);
            return redirect()->route('teacher.dashboard');
        }

        // Nếu không có sinh viên hoặc giáo viên phù hợp
        return back()->with(['alert' => 'Thông tin đăng nhập không đúng']);
    }


    // Đăng xuất
    public function logout(Request $request)
    {
        Auth::logout(); // Xóa session của người dùng

        $request->session()->invalidate(); // Hủy session

        $request->session()->regenerateToken(); // Regenerate CSRF token

        return redirect('/login'); // Chuyển hướng người dùng đến trang đăng nhập
    }
}
