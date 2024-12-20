<?php

namespace App\Http\Controllers;

use App\Models\LopHoc;
use App\Models\BaiGiang;
use App\Models\QuanLyHS;
use App\Models\SinhVien;
use App\Models\BaiKiemTra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

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

    public function studentPassword()
    {
        $user = Session::get('user');
        return view('student.password', compact('user'));
    }

    public function changeStudentPassword(Request $request)
    {
        $user = Session::get('user');
        $user = SinhVien::find($user['id']);

        if (!$user) {
            return back()->with('error', 'Không tìm thấy người dùng.');
        }

        $request->validate([
            'pass_old' => 'required',
            'pass_new' => 'required|min:8',
            'pass_newcf' => 'required|same:pass_new',
        ]);

        // Kiểm tra có trùng mật khẩu cũ không
        if ($request->pass_old !== $user->password_sv) {
            return back()->with('error', 'Mật khẩu cũ không đúng.');
        }

        // Cập nhật mật khẩu mới
        $user->password_sv = $request->pass_new;
        $user->save();

        return redirect()->route('student.password')->with('success', 'Mật khẩu đã được thay đổi thành công.');
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
                'quanLyGV.giaoVien',
                'baiGiang',
                'baiKiemTra'
            ])
            ->first();

        if (!$class) {
            return redirect()->route('student.classlist')->withErrors(['error' => 'Lớp không tồn tại']);
        }

        return view('student.view.classes', compact('class'));
    }

    public function viewTest($malop)
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

        return view('student.view.tests', [
            'class' => $class,
            'tests' => $class->baiKiemTra,
        ]);
    }


    public function viewLecture($malop)
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

        // Lấy danh sách bài giảng từ lớp học
        $class = LopHoc::where('malop', $malop)->with('baiGiang')->first();

        if (!$class) {
            return redirect()->route('student.classlist')->withErrors(['error' => 'Lớp không tồn tại']);
        }

        $lectures = $class->baiGiang;

        return view('student.view.lectures', compact('class', 'lectures'));
    }

    public function viewMember($malop)
    {
        $class = LopHoc::where('malop', $malop)
            ->with([
                'quanLyHS',  // Liên kết với QuanLyHS để lấy sinh viên
                'quanLyGV',
                'baiGiang',
                'baiKiemTra'
            ])
            ->first();

        if (!$class) {
            return redirect()->route('student.classlist')->withErrors(['error' => 'Lớp không tồn tại']);
        }

        // Lấy danh sách sinh viên qua QuanLyHS và với mối quan hệ sinhVien
        $members = QuanLyHS::where('malop', $malop)
            ->with('sinhVien')  // Liên kết với bảng SinhVien
            ->get();

        return view('student.view.members', compact('class', 'members'));
    }

    public function viewScore($malop)
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

        return view('student.view.scores', compact('class'));
    }

    public function show($id)
    {
        // Tìm bài giảng theo ID
        $lecture = BaiGiang::findOrFail($id);

        // Trả về view chi tiết bài giảng
        return view('student.detail.lecture', compact('lecture'));
    }

    // Chuyển hướng đến đúng dạng bài kiểm tra
    public function redirectToTest($malop, $msbkt)
    {
        $test = BaiKiemTra::find($msbkt);

        if (!$test) {
            return redirect()->back()->with('error', 'Bài kiểm tra không tồn tại');
        }

        // Lấy tất cả bài kiểm tra của lớp để truyền vào session
        $class = LopHoc::where('malop', $malop)->with('baiKiemTra')->first();
        if ($class) {
            session(['tests' => $class->baiKiemTra]); // Lưu danh sách bài kiểm tra vào session
        }

        // Kiểm tra loại bài kiểm tra và chuyển hướng
        if ($test->loai_bkt == 'TuLuan') {
            return redirect()->route('student.test.essay', ['malop' => $malop, 'msbkt' => $msbkt]);
        } elseif ($test->loai_bkt == 'TracNghiem') {
            return redirect()->route('student.test.form', ['malop' => $malop, 'msbkt' => $msbkt]);
        } else {
            return redirect()->back()->with('error', 'Loại bài kiểm tra không hợp lệ');
        }
    }

    public function takeTestForm($malop, $msbkt)
    {
        $class = LopHoc::where('malop', $malop)
            ->with([
                'quanLyHS',
                'quanLyGV',
                'baiGiang',
                'baiKiemTra'
            ])->first();
        if (!$class) {
            return redirect()->route('student.classlist')->withErrors(['error' => 'Lớp không tồn tại']);
        }

        $test = BaiKiemTra::where('msbkt', $msbkt)->with('cauHoi')->first();
        if (!$test) {
            return redirect()->route('student.classlist')->withErrors(['error' => 'Bài kiểm tra không tồn tại']);
        }

        return view('student.test.form', compact('class', 'test', 'msbkt'));
    }

    public function takeTestEssay($malop, $msbkt)
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

        return view('student.test.essay', compact('class', 'msbkt'));
    }
}
