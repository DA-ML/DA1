<?php

namespace App\Http\Controllers;

use App\Models\LopHoc;
use App\Models\BaiGiang;
use App\Models\QuanLyHS;
use App\Models\SinhVien;
use App\Models\BaiKiemTra;
use App\Models\CauHoi;
use App\Models\ChuanDauRa;
use App\Models\LichSuLamBaiKiemTra;
use App\Models\KetQuaBaiKiemTra;
use App\Models\SinhVienKetQua;
use App\Models\KetQuaChuans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

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
        // Lấy thông tin người dùng từ session
        $user = Session::get('user');

        // Lấy thông tin lớp học
        $class = LopHoc::where('malop', $malop)
            ->with([
                'quanLyHS',
                'quanLyGV',
                'baiGiang',
                'baiKiemTra' // Lấy danh sách bài kiểm tra liên quan
            ])
            ->first();

        if (!$class) {
            return redirect()->route('student.classlist')->withErrors(['error' => 'Lớp không tồn tại']);
        }

        // Lấy danh sách bài kiểm tra cho lớp học
        $tests = $class->baiKiemTra;

        // Lặp qua từng bài kiểm tra để lấy số lần làm bài của sinh viên
        foreach ($tests as $test) {
            // Lấy số lần làm bài của sinh viên cho từng bài kiểm tra
            $test->numAttempts = LichSuLamBaiKiemTra::where('msbkt', $test->msbkt)
                ->where('mssv', $user['id']) // Lọc theo mã sinh viên, dùng id từ session
                ->sum('solanlam'); // Tính tổng số lần làm bài
        }

        return view('student.view.tests', [
            'class' => $class,
            'tests' => $tests,
            'malop' => $malop,
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
        $user = Session::get('user'); // Lấy dữ liệu user từ session
        $studentId = $user['id']; // Truy cập mssv từ mảng

        // Kiểm tra lớp học tồn tại
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

        // Lấy danh sách các bài kiểm tra của lớp
        $baiKiemTras = BaiKiemTra::where('malop', $malop)->get();

        // Lấy điểm cao nhất của sinh viên cho mỗi bài kiểm tra
        $scores = $baiKiemTras->map(function ($baiKiemTra) use ($studentId) {
            $ketQua = KetQuaBaiKiemTra::where('msbkt', $baiKiemTra->msbkt)
                ->where('mssv', $studentId)
                ->orderByDesc('diem') // Sắp xếp điểm theo thứ tự giảm dần
                ->first(); // Lấy điểm cao nhất

            return [
                'tenbkt' => $baiKiemTra->tenbkt,
                'diem' => $ketQua ? $ketQua->diem : '-' // Dấu '-' nếu không có điểm
            ];
        });

        // Tính điểm trung bình, chỉ tính với các bài có điểm hợp lệ
        $averageScore = $scores->filter(fn($score) => is_numeric($score['diem']))
            ->avg(fn($score) => $score['diem']);

        return view('student.view.scores', compact('class', 'scores', 'averageScore'));
    }

    public function show($id)
    {
        // Tìm bài giảng theo ID
        $lecture = BaiGiang::findOrFail($id);

        // Trả về view chi tiết bài giảng
        return view('student.detail.lecture', compact('lecture'));
    }

    // Chuyển hướng đến đúng dạng bài kiểm tra, kiểm tra số lần đã làm của sinh viên
    public function redirectToTest($malop, $msbkt)
    {
        $user = Session::get('user');
        if (!isset($user['id'])) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập trước khi làm bài.');
        }

        $mssv = $user['id'];

        // Lấy thông tin bài kiểm tra từ bảng BaiKiemTra
        $test = BaiKiemTra::find($msbkt);
        if (!$test) {
            return redirect()->back()->with('error', 'Bài kiểm tra không tồn tại.');
        }

        // Kiểm tra thể loại
        if ($test->loai_bkt == 'TuLuan') {
            // Nếu là bài tự luận, chuyển hướng đến trang essay
            return redirect()->route('student.test.essay', ['malop' => $malop, 'msbkt' => $msbkt, 'test' => $test]);
        } elseif ($test->loai_bkt == 'TracNghiem') {
            // Nếu là bài trắc nghiệm, kiểm tra số lần làm bài
            $lichSuLamBai = LichSuLamBaiKiemTra::where('msbkt', $msbkt)
                ->where('mssv', $mssv)
                ->first();

            if ($lichSuLamBai && $lichSuLamBai->solanlam >= $test->solanlam) {
                // Nếu số lần làm bài đã đạt giới hạn, hiển thị thông báo lỗi
                return redirect()->back()->with('error', 'Số lần làm bài đã hết!');
            }

            // Nếu chưa đạt giới hạn số lần làm bài, chuyển đến trang làm bài trắc nghiệm
            if ($lichSuLamBai) {
                $lichSuLamBai->solanlam += 1;
                $lichSuLamBai->save();
            } else {
                // Nếu chưa có lịch sử làm bài, tạo mới
                LichSuLamBaiKiemTra::create([
                    'msbkt' => $msbkt,
                    'mssv' => $mssv,
                    'malop' => $malop,
                    'solanlam' => 1,
                ]);
            }

            // Chuyển hướng đến trang làm bài trắc nghiệm
            return redirect()->route('student.test.form', ['malop' => $malop, 'msbkt' => $msbkt]);
        }

        // Nếu thể loại không phải là TuLuan hay TracNghiem, trả về lỗi hoặc thông báo khác
        return redirect()->back()->with('error', 'Loại bài kiểm tra không hợp lệ.');
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

        $mssv = session('user.id');

        return view('student.test.form', compact('class', 'test', 'msbkt', 'malop', 'mssv'));
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

        $test = BaiKiemTra::where('msbkt', $msbkt)->with('cauHoi')->first();
        if (!$test) {
            return redirect()->route('student.classlist')->withErrors(['error' => 'Bài kiểm tra không tồn tại']);
        }

        $mssv = session('user.id');

        return view('student.test.essay', compact('class', 'msbkt', 'malop', 'mssv', 'test'));
    }

    public function storeStudentTest(Request $request, $malop)
    {
        $answers = $request->input('answers', []);
        $msbkt = $request->input('msbkt');
        $mssv = $request->input('mssv');

        $finalAnswers = [];
        $cauHoi = DB::table('CauHoi')->where('msbkt', $msbkt)->get();

        foreach ($cauHoi as $index => $cau) {
            $finalAnswers[$cau->msch] = $answers[$index] ?? null;
        }

        $lichSuLamBai = LichSuLamBaiKiemTra::where('msbkt', $msbkt)
            ->where('mssv', $mssv)
            ->where('malop', $malop)
            ->first();

        if (!$lichSuLamBai) {
            return redirect()->route('student.class.tests', ['malop' => $malop])
                ->with('error', 'Không tìm thấy lịch sử làm bài!'); // Nếu không tìm thấy lịch sử làm bài, trả về thông báo lỗi
        }

        $cauHoi = DB::table('CauHoi')
            ->where('msbkt', $msbkt)
            ->get();

        $soCauDungTheoChuan = [];
        $tongDiem = 0;
        logger()->info("Mảng answers từ request: " . json_encode($answers));

        // Chấm điểm
        foreach ($cauHoi as $cau) {
            $answer = isset($finalAnswers[$cau->msch]) ? $finalAnswers[$cau->msch] : null;
            logger()->info("Câu hỏi {$cau->msch}: câu trả lời = " . json_encode($answer));

            if ($answer === null || trim($answer) === "") {
                $answer = null; // Câu trả lời trống được xem là sai
                continue;
            }

            if (trim((string) $answer) === trim((string) $cau->dapan)) {
                $tongDiem += $cau->diem;
                if (!isset($soCauDungTheoChuan[$cau->chuan_id])) {
                    $soCauDungTheoChuan[$cau->chuan_id] = 0;
                }
                $soCauDungTheoChuan[$cau->chuan_id]++;
            }
        }

        $ketQuaBaiKiemTra = KetQuaBaiKiemTra::create([
            'msbkt' => $msbkt,
            'mssv' => $mssv,
            'diem' => $tongDiem,
            'cau_tra_loi' => json_encode($finalAnswers, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'lich_su_id' => $lichSuLamBai->id,
        ]);

        $sinhVienKetQua = SinhVienKetQua::firstOrCreate(
            [
                'mssv' => $mssv,
                'msbkt' => $msbkt,
                'malop' => $malop,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        );

        foreach ($soCauDungTheoChuan as $chuanId => $soCauDung) {
            KetQuaChuans::updateOrCreate(
                [
                    'sinhvien_ketqua_id' => $sinhVienKetQua->id,
                    'chuan_id' => $chuanId,
                ],
                ['so_cau_dung' => $soCauDung]
            );
        }

        return redirect()->route('student.class.tests', ['malop' => $malop])
            ->with('success', 'Lưu bài kiểm tra và chấm điểm thành công!');
    }

    public function viewTestDetail($malop, $msbkt)
    {
        $test = BaiKiemTra::where('msbkt', $msbkt)->with('cauHoi')->first();
        if (!$test) {
            return redirect()->route('student.classlist')->withErrors(['error' => 'Bài kiểm tra không tồn tại']);
        }

        // Trả về view với dữ liệu
        return view('student.detail.test', [
            'malop' => $malop,
            'msbkt' => $msbkt,
            'test' => $test,
        ]);
    }

    // Lưu bài kiểm tra tự luận
    public function storeEssayTest(Request $request, $malop)
    {
        $msbkt = $request->input('msbkt');
        $mssv = $request->input('mssv');

        if ($request->hasFile('file')) {
            $file = $request->file('file');

            $filePath = "essay/{$msbkt}/{$mssv}";
            $fileName = $file->getClientOriginalName();
            $fullPath = public_path("{$filePath}/{$fileName}");

            // Kiểm tra xem bài làm đã tồn tại
            $existingEntry = KetQuaBaiKiemTra::where('msbkt', $msbkt)->where('mssv', $mssv)->first();

            if ($existingEntry) {
                // Xóa file cũ nếu tồn tại
                if (file_exists(public_path($existingEntry->files_path))) {
                    unlink(public_path($existingEntry->files_path));
                }

                // Cập nhật đường dẫn file mới
                $existingEntry->update([
                    'files_path' => "{$filePath}/{$fileName}",
                ]);
            } else {
                // Tạo mới nếu chưa tồn tại
                KetQuaBaiKiemTra::create([
                    'msbkt' => $msbkt,
                    'mssv' => $mssv,
                    'diem' => null,
                    'files_path' => "{$filePath}/{$fileName}",
                ]);
            }

            SinhVienKetQua::firstOrCreate(
                [
                    'msbkt' => $msbkt,
                    'mssv' => $mssv,
                    'malop' => $malop,
                ],
                [
                    'updated_at' => now(),
                ]
            );


            // Lưu file vào thư mục
            $file->move(public_path($filePath), $fileName);

            return redirect()->route('student.class.tests', ['malop' => $malop])
                ->with('success', 'Bài tự luận đã được nộp thành công!');
        }

        return redirect()->route('student.class.tests', ['malop' => $malop])
            ->withErrors(['error' => 'Vui lòng tải lên file trước khi nộp!']);
    }

    public function viewEssayDetail($malop, $msbkt)
    {
        // Lấy thông tin bài kiểm tra từ bảng LichSuLamBaiKiemTra
        $test = LichSuLamBaiKiemTra::where('msbkt', $msbkt)
            ->where('malop', $malop)
            ->first();

        // Nếu có bài kiểm tra hợp lệ, trả về trang chi tiết
        return view('student.detail.essay', ['test' => $test, 'malop' => $malop]);
    }
}
