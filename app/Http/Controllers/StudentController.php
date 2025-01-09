<?php

namespace App\Http\Controllers;

use App\Models\LopHoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Psy\Readline\Hoa\Console;

class StudentController extends Controller
{
    public function studentDashboard()
    {
        return view('student.dashboard');
    }

    public function studentProfile()
    {
        $user = Session::get('user');
        return view('student.profile', compact('user'));
    }

    public function studentChangePw()
    {
        return view('student.changepw');
    }

    public function classList()
    {
        $user = Session::get('user');

        if (!$user || $user['role'] !== 'sinhvien') {
            return redirect()->route('login')->withErrors(['error' => 'Bạn không có quyền truy cập']);
        }

        // Truy vấn lớp học mà học sinh đang học
        $classes = LopHoc::whereHas('quanLyHS', function ($query) use ($user) {
            $query->where('mssv', $user['id']);
        })
            ->withCount([
                'quanLyHS as so_hoc_sinh', // Đếm số học sinh
                'baiGiang as so_bai_giang', // Đếm số bài giảng
                'baiKiemTra as so_bai_kiem_tra', // Đếm số bài kiểm tra
            ])
            ->get();

        return view('student.classlist', compact('classes'));
    }

    public function viewClass($malop)
    {
        $class = LopHoc::where('malop', $malop)
            ->with([
                'quanLyHS',
                'quanLyGV',
                'baiGiang',
                'baiKiemTra'
            ])
            ->first();

        if (!$class) {
            return redirect()->route('student.classlist')->withErrors(['error' => 'Lớp không tồn tại']);
        }

        return view('student.view.classes', compact('class'));
    }

    public function classLecture($malop)
    {
        $user = Session::get('user');

        $class = LopHoc::where('malop', $malop)
            ->with([
                'quanLyHS',
                'quanLyGV',
                'baiGiang',
                'baiKiemTra'
            ])
            ->first();
        if (!$class) {
            return redirect()->route('student.classlist')->withErrors(['error' => 'Lớp không tồn tại']);
        }

        // Lấy danh sách bài giảng từ lớp học
        $class = LopHoc::where('malop', $malop)->with('baiGiang')->first();

        if (!$class) {
            return redirect()->route('student.classlist')->withErrors(['error' => 'Lớp không tồn tại']);
        }

        $lectures = $class->baiGiang;

        return view('student.view.lectures', compact('lectures', 'class'));
    }

    public function classTest($malop)
    {
        $user = Session::get('user');

        $class = LopHoc::where('malop', $malop)
            ->with([
                'quanLyHS',
                'quanLyGV',
                'baiGiang',
                'baiKiemTra'
            ])
            ->first();
        if (!$class) {
            return redirect()->route('student.classlist')->withErrors(['error' => 'Lớp không tồn tại']);
        }

        // Lấy danh sách bài tập từ lớp học
        $class = LopHoc::where('malop', $malop)->with('baiKiemTra')->first();
        if (!$class) {
            return redirect()->route('student.classlist')->withErrors(['error' => 'Lớp không tồn tại']);
        }

        $tests = $class->baiKiemTra;

        return view('student.view.tests', compact('class', 'tests'));
    }

    public function classMember($malop)
    {
        $user = Session::get('user');

        $class = LopHoc::where('malop', $malop)
            ->with([
                'quanLyHS',
                'quanLyGV',
                'baiGiang',
                'baiKiemTra'
            ])
            ->first();
        if (!$class) {
            return redirect()->route('student.classlist')->withErrors(['error' => 'Lớp không tồn tại']);
        }

        $class = LopHoc::where('malop', $malop)->with('thanhVien')->first();
        if (!$class) {
            return redirect()->route('student.classlist')->withErrors(['error' => 'Lớp không tồn tại']);
        }

        $members = $class->thanhVien;

        return view('student.view.members', compact('class'));
    }
}