
<?php

namespace App\Http\Controllers;

use App\Models\LopHoc;
use App\Models\BaiGiang;
use App\Models\CauHoi;
use App\Models\BaiKiemTra;
use App\Models\ThanhPhanDanhGia;
use App\Models\ChuanDauRa;
use App\Models\GiaoVien;
use App\Models\QuanLyHS;
use App\Models\KetQuaBaiKiemTra;
use App\Models\KetQuaChuans;
use App\Models\SinhVien;
use App\Models\SinhVienKetQua;
use App\Models\NhanXetBaiKiemTra;
use App\Models\BaiKiemTraTile;
use App\Models\KetQuaThanhPhan;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    protected $currentSemester;
    protected $currentYear;

    // Constructor để khởi tạo giá trị
    public function __construct()
    {
        $this->currentSemester = '1';
        $this->currentYear = '2023-2024';
    }
    public function teacherDashboard()
    {
        $user = Session::get('user');
        $teacher = GiaoVien::find($user['id']);

        if (!$teacher) {
            return back()->with('error', 'Không tìm thấy người dùng.');
        }

        $classes = DB::table('QuanLyGV')
            ->join('LopHoc', 'QuanLyGV.malop', '=', 'LopHoc.malop')
            ->join('HocKy', 'QuanLyGV.mahk', '=', 'HocKy.mahk')
            ->join('Khoa', 'LopHoc.makhoa', '=', 'Khoa.makhoa')
            ->where('QuanLyGV.msgv', $teacher->msgv)
            ->where('HocKy.tenhk', $this->currentSemester)
            ->where('HocKy.namhoc', $this->currentYear)
            ->select(
                'LopHoc.malop',
                'LopHoc.tenlop',
                'Khoa.tenkhoa',
                'HocKy.tenhk',
                'HocKy.namhoc'
            )
            ->get();

        $lectures = DB::table('QuanLyGV')
            ->join('LopHoc', 'QuanLyGV.malop', '=', 'LopHoc.malop')
            ->join('HocKy', 'QuanLyGV.mahk', '=', 'HocKy.mahk')
            ->leftJoin('BaiGiang', 'LopHoc.malop', '=', 'BaiGiang.malop')
            ->where('QuanLyGV.msgv', $teacher->msgv)
            ->where('HocKy.tenhk', $this->currentSemester)
            ->where('HocKy.namhoc', $this->currentYear)
            ->select('BaiGiang.tenbg', 'BaiGiang.noidungbg', 'BaiGiang.file_paths', 'BaiGiang.link_paths')
            ->get();

        $exams = DB::table('QuanLyGV')
            ->join('LopHoc', 'QuanLyGV.malop', '=', 'LopHoc.malop')
            ->join('HocKy', 'QuanLyGV.mahk', '=', 'HocKy.mahk')
            ->leftJoin('BaiKiemTra', 'LopHoc.malop', '=', 'BaiKiemTra.malop')
            ->where('QuanLyGV.msgv', $teacher->msgv)
            ->where('HocKy.tenhk', $this->currentSemester)
            ->where('HocKy.namhoc', $this->currentYear)
            ->select('BaiKiemTra.tenbkt', 'BaiKiemTra.ngaybatdau', 'BaiKiemTra.ngayketthuc', 'BaiKiemTra.file_path', 'BaiKiemTra.loai_bkt', 'BaiKiemTra.num_ques')
            ->get();

        return view('teacher.dashboard', compact('teacher', 'classes', 'lectures', 'exams'))->with([
            'currentSemester' => $this->currentSemester,
            'currentYear' => $this->currentYear
        ]);
    }

    public function classList()
    {
        $user = Session::get('user');
        $teacher = GiaoVien::find($user['id']);

        if (!$teacher) {
            return back()->with('error', 'Không tìm thấy người dùng.');
        }

        // Truy vấn lớp học mà giáo viên quản lý
        $classes = DB::select(
            'SELECT
                LopHoc.malop,
                LopHoc.tenlop,
                COUNT(DISTINCT QuanLyHS.mssv) AS so_hoc_sinh,
                COUNT(DISTINCT BaiGiang.msbg) AS so_bai_giang,
                COUNT(DISTINCT BaiKiemTra.msbkt) AS so_bai_kiem_tra
            FROM LopHoc
            JOIN QuanLyGV ON LopHoc.malop = QuanLyGV.malop
            JOIN HocKy ON QuanLyGV.mahk = HocKy.mahk
            LEFT JOIN QuanLyHS ON LopHoc.malop = QuanLyHS.malop AND QuanLyHS.mahk = HocKy.mahk
            LEFT JOIN BaiGiang ON LopHoc.malop = BaiGiang.malop
            LEFT JOIN BaiKiemTra ON LopHoc.malop = BaiKiemTra.malop
            LEFT JOIN Khoa ON LopHoc.makhoa = Khoa.makhoa
            WHERE QuanLyGV.msgv = :msgv
            AND HocKy.tenhk = :currentSemester
            AND HocKy.namhoc = :currentYear
            GROUP BY LopHoc.malop, LopHoc.tenlop, Khoa.tenkhoa, HocKy.tenhk, HocKy.namhoc',
            [
                'msgv' => $teacher->msgv,
                'currentSemester' => $this->currentSemester,
                'currentYear' => $this->currentYear
            ]
        );

        return view('teacher.classlist', compact('classes'));
    }


    public function teacherProfile()
    {
        $user = Session::get('user');
        return view('teacher.profile', compact('user'));
    }
    public function teacherCalendar()
    {
        $user = Session::get('user');
        $teacher = GiaoVien::find($user['id']);

        if (!$teacher) {
            return back()->with('error', 'Không tìm thấy người dùng.');
        }

        $exams = DB::table('QuanLyGV')
            ->join('LopHoc', 'QuanLyGV.malop', '=', 'LopHoc.malop')
            ->join('HocKy', 'QuanLyGV.mahk', '=', 'HocKy.mahk')
            ->leftJoin('BaiKiemTra', 'LopHoc.malop', '=', 'BaiKiemTra.malop')
            ->where('QuanLyGV.msgv', $teacher->msgv)
            ->where('HocKy.tenhk', $this->currentSemester)
            ->where('HocKy.namhoc', $this->currentYear)
            ->select('BaiKiemTra.ngaybatdau')
            ->pluck('ngaybatdau')
            ->filter(function ($date) {
                return $date !== null;
            })
            ->map(function ($date) {
                return date('Y-m-d', strtotime($date));
            })
            ->toArray();

        // Reset key của mảng
        $examDates = array_values($exams);

        return view('teacher.calendar', compact('user', 'examDates'));
    }


    public function teacherPassword()
    {
        $user = Session::get('user');
        return view('teacher.password', compact('user'));
    }

    public function changePassword(Request $request)
    {
        $user = Session::get('user');
        $user = GiaoVien::find($user['id']);

        if (!$user) {
            return back()->with('error', 'Không tìm thấy người dùng.');
        }

        // Validate input từ form
        $request->validate([
            'pass_old' => 'required',
            'pass_new' => 'required|min:8',
            'pass_newcf' => 'required|same:pass_new',
        ]);

        // Kiểm tra có trùng mật khẩu cũ không
        if ($request->pass_old !== $user->password_gv) {
            return back()->with('error', 'Mật khẩu cũ không đúng.');
        }

        // Cập nhật mật khẩu mới
        $user->password_gv = $request->pass_new;
        $user->save();

        return redirect()->route('teacher.password')->with('alert', 'Mật khẩu đã được thay đổi thành công.');
    }

    public function viewClass($malop)
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
            return redirect()->route('teacher.classlist')->withErrors(['error' => 'Lớp không tồn tại']);
        }

        // Chuyển kết quả vào view
        return view('teacher.view.classes', compact('class'));
    }

    public function showNotification($malop)
    {
        // Lấy thông tin lớp học dựa vào mã lớp
        $class = LopHoc::where('malop', $malop)->first();

        if (!$class) {
            return redirect()->route('teacher.classlist')->withErrors(['error' => 'Lớp không tồn tại']);
        }

        return view('teacher.update.notification', compact('class'));
    }

    public function updateNotification(Request $request, $malop)
    {
        // Validate dữ liệu đầu vào
        $request->validate([
            'mota' => 'nullable|string|max:500',
        ]);

        // Lấy thông tin lớp học dựa vào mã lớp
        $class = LopHoc::where('malop', $malop)->first();

        if (!$class) {
            return redirect()->route('teacher.classlist')->withErrors(['alert' => 'Lớp không tồn tại']);
        }

        // Cập nhật mô tả
        $class->mota = $request->input('mota');
        $class->save();

        session()->flash('alert', 'Cập nhật thông báo thành công.');

        return redirect()->route('class.details', ['malop' => $malop]);
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
            return redirect()->route('teacher.classlist')->withErrors(['error' => 'Lớp không tồn tại']);
        }

        // Lấy danh sách bài tập từ lớp học
        $class = LopHoc::where('malop', $malop)->with('baiKiemTra')->first();
        if (!$class) {
            return redirect()->route('teacher.classlist')->withErrors(['error' => 'Lớp không tồn tại']);
        }

        $tests = $class->baiKiemTra;
        $hasTestPercent = BaiKiemTraTiLe::where('malop', $malop)->exists();

        return view('teacher.view.tests', compact('class', 'tests', 'hasTestPercent'));
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
            return redirect()->route('teacher.classlist')->withErrors(['error' => 'Lớp không tồn tại']);
        }

        // Lấy danh sách bài giảng từ lớp học
        $class = LopHoc::where('malop', $malop)->with('baiGiang')->first();

        if (!$class) {
            return redirect()->route('teacher.classlist')->withErrors(['error' => 'Lớp không tồn tại']);
        }

        $lectures = $class->baiGiang;

        return view('teacher.view.lectures', compact('lectures', 'class'));
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
            return redirect()->route('teacher.classlist')->withErrors(['error' => 'Lớp không tồn tại']);
        }

        // Lấy danh sách sinh viên qua QuanLyHS
        $members = QuanLyHS::where('malop', $malop)
            ->with('sinhVien')
            ->get();

        return view('teacher.view.members', compact('class', 'members'));
    }

    public function classStatics($malop)
    {
        $user = Session::get('user');

        // Lấy thông tin lớp
        $class = LopHoc::where('malop', $malop)
            ->with([
                'quanLyHS',
                'quanLyGV',
                'baiGiang',
                'baiKiemTra'
            ])
            ->first();

        if (!$class) {
            return redirect()->route('teacher.classlist')->withErrors(['error' => 'Lớp không tồn tại']);
        }

        // Thống kê điểm số
        $scoreStatistics = DB::select("
    SELECT
    category,
    COUNT(*) AS count
FROM (
    SELECT
        sv.mssv AS MaSinhVien,
        sv.tensv AS TenSinhVien,
        AVG(kq.diem) AS avg_score,
        CASE
            WHEN AVG(kq.diem) >= 8 THEN 'Giỏi'
            WHEN AVG(kq.diem) >= 7 THEN 'Khá'
            WHEN AVG(kq.diem) >= 5 THEN 'Trung bình'
            ELSE 'Yếu'
        END AS category
    FROM
        SinhVien sv
    JOIN QuanLyHS qlhs ON sv.mssv = qlhs.mssv
    JOIN BaiKiemTra bkt ON bkt.malop = qlhs.malop
    JOIN KetQuaBaiKiemTra kq ON sv.mssv = kq.mssv AND bkt.msbkt = kq.msbkt
    WHERE
        qlhs.malop = ?
    GROUP BY
        sv.mssv, sv.tensv
) AS subquery
GROUP BY
    category
ORDER BY
    count DESC;

", [$malop]);


        $members = QuanLyHS::where('malop', $malop)
            ->with('sinhVien')
            ->get();

        // Lấy thông tin bài kiểm tra trong lớp
        $baiKiemTras = BaiKiemTra::where('malop', $malop)->get();

        // Tạo danh sách sinh viên kèm điểm cao nhất từng bài kiểm tra và tính điểm trung bình
        $studentsWithResults = $members->map(function ($member) use ($baiKiemTras) {
            $student = $member->sinhVien;

            // Tính điểm cho mỗi bài kiểm tra của sinh viên
            $results = $baiKiemTras->map(function ($baiKiemTra) use ($student) {
                $highestScore = KetQuaBaiKiemTra::where('msbkt', $baiKiemTra->msbkt)
                    ->where('mssv', $student->mssv)
                    ->max('diem'); // Lấy điểm cao nhất

                return $highestScore !== null ? $highestScore : 0;
            });

            // Tính điểm trung bình của sinh viên
            $averageScore = count($results) > 0 ? array_sum($results->toArray()) / count($results) : 0;

            return [
                'sinh_vien' => $student,
                'ket_qua' => $results,
                'diem_trung_binh' => $averageScore
            ];
        });

        // Sắp xếp sinh viên theo điểm trung bình từ cao đến thấp và lấy 5 sinh viên đầu tiên
        $topStudents = $studentsWithResults->sortByDesc('diem_trung_binh')->take(5);

        // Thêm STT (số thứ tự) vào mảng sinh viên
        $leaderboard = $topStudents->map(function ($student, $index) {
            return [
                'stt' => $index + 1,
                'tensv' => $student['sinh_vien']->ten, // Giả sử tên sinh viên là `ten`
                'mssv' => $student['sinh_vien']->mssv, // Giả sử MSSV là `mssv`
                'diem_tb' => $student['diem_trung_binh']
            ];
        });

        // Thống kê kết quả thành phần A1 và chuẩn đầu ra G2.2, G3.1
        $resultStatistics = KetQuaThanhPhan::where('malop', $malop)
            ->where('thanhphan_id', 'A1')  // Lọc theo thành phần A1
            ->whereIn('chuan_id', ['G2.2', 'G3.1'])  // Lọc theo các chuẩn đầu ra
            ->get();

        // Khởi tạo mảng thống kê cho kết quả
        $resultStats = [
            'G2.2' => ['gioi' => 0, 'kha' => 0, 'trungbinh' => 0, 'yeu' => 0],
            'G3.1' => ['gioi' => 0, 'kha' => 0, 'trungbinh' => 0, 'yeu' => 0],
        ];

        // Phân loại sinh viên theo tỷ lệ
        foreach ($resultStatistics as $result) {
            $tyle = $result->tyle;
            $chuan = $result->chuan_id;

            // Xác định loại
            if ($tyle >= 80) {
                $category = 'gioi';
            } elseif ($tyle >= 70) {
                $category = 'kha';
            } elseif ($tyle >= 50) {
                $category = 'trungbinh';
            } else {
                $category = 'yeu';
            }

            // Cập nhật thống kê theo chuẩn đầu ra và loại
            if (isset($resultStats[$chuan])) {
                $resultStats[$chuan][$category]++;
            }
        }

        // Dữ liệu cho biểu đồ
        $chartData = [
            ['Chuan Dau Ra', 'Giỏi', 'Khá', 'Trung Bình', 'Yếu'],
            ['G2.2', $resultStats['G2.2']['gioi'], $resultStats['G2.2']['kha'], $resultStats['G2.2']['trungbinh'], $resultStats['G2.2']['yeu']],
            ['G3.1', $resultStats['G3.1']['gioi'], $resultStats['G3.1']['kha'], $resultStats['G3.1']['trungbinh'], $resultStats['G3.1']['yeu']],
        ];

        // Thống kê kết quả thành phần A3 và chuẩn đầu ra G2.2, G3.1, G3.2
        $resultStatisticsA3 = KetQuaThanhPhan::where('malop', $malop)
            ->where('thanhphan_id', 'A3')  // Lọc theo thành phần A3
            ->whereIn('chuan_id', ['G2.2', 'G3.1', 'G3.2'])  // Lọc theo các chuẩn đầu ra
            ->get();

        // Khởi tạo mảng thống kê cho kết quả A3
        $resultStatsA3 = [
            'G2.2' => ['gioi' => 0, 'kha' => 0, 'trungbinh' => 0, 'yeu' => 0],
            'G3.1' => ['gioi' => 0, 'kha' => 0, 'trungbinh' => 0, 'yeu' => 0],
            'G3.2' => ['gioi' => 0, 'kha' => 0, 'trungbinh' => 0, 'yeu' => 0],
        ];

        // Phân loại sinh viên theo tỷ lệ cho A3
        foreach ($resultStatisticsA3 as $result) {
            $tyle = $result->tyle;
            $chuan = $result->chuan_id;

            // Xác định loại
            if ($tyle >= 80) {
                $category = 'gioi';
            } elseif ($tyle >= 70) {
                $category = 'kha';
            } elseif ($tyle >= 50) {
                $category = 'trungbinh';
            } else {
                $category = 'yeu';
            }

            // Cập nhật thống kê theo chuẩn đầu ra và loại
            if (isset($resultStatsA3[$chuan])) {
                $resultStatsA3[$chuan][$category]++;
            }
        }

        // Dữ liệu cho biểu đồ A3
        $chartDataA3 = [
            ['Chuan Dau Ra', 'Giỏi', 'Khá', 'Trung Bình', 'Yếu'],
            ['G2.2', $resultStatsA3['G2.2']['gioi'], $resultStatsA3['G2.2']['kha'], $resultStatsA3['G2.2']['trungbinh'], $resultStatsA3['G2.2']['yeu']],
            ['G3.1', $resultStatsA3['G3.1']['gioi'], $resultStatsA3['G3.1']['kha'], $resultStatsA3['G3.1']['trungbinh'], $resultStatsA3['G3.1']['yeu']],
            ['G3.2', $resultStatsA3['G3.2']['gioi'], $resultStatsA3['G3.2']['kha'], $resultStatsA3['G3.2']['trungbinh'], $resultStatsA3['G3.2']['yeu']],
        ];

        // Thống kê kết quả thành phần A3 và chuẩn đầu ra G2.2, G3.1, G3.2, G6.1
        $resultStatisticsA4 = KetQuaThanhPhan::where('malop', $malop)
            ->where('thanhphan_id', 'A4')  // Lọc theo thành phần A4
            ->whereIn('chuan_id', ['G2.2', 'G3.1', 'G3.2', 'G6.1'])  // Lọc theo các chuẩn đầu ra
            ->get();

        // Khởi tạo mảng thống kê cho kết quả A4
        $resultStatsA4 = [
            'G2.2' => ['gioi' => 0, 'kha' => 0, 'trungbinh' => 0, 'yeu' => 0],
            'G3.1' => ['gioi' => 0, 'kha' => 0, 'trungbinh' => 0, 'yeu' => 0],
            'G3.2' => ['gioi' => 0, 'kha' => 0, 'trungbinh' => 0, 'yeu' => 0],
            'G6.1' => ['gioi' => 0, 'kha' => 0, 'trungbinh' => 0, 'yeu' => 0],
        ];

        // Phân loại sinh viên theo tỷ lệ cho A4
        foreach ($resultStatisticsA4 as $result) {
            $tyle = $result->tyle;
            $chuan = $result->chuan_id;

            // Xác định loại
            if ($tyle >= 80) {
                $category = 'gioi';
            } elseif ($tyle >= 70) {
                $category = 'kha';
            } elseif ($tyle >= 50) {
                $category = 'trungbinh';
            } else {
                $category = 'yeu';
            }

            // Cập nhật thống kê theo chuẩn đầu ra và loại
            if (isset($resultStatsA4[$chuan])) {
                $resultStatsA4[$chuan][$category]++;
            }
        }

        // Dữ liệu cho biểu đồ A4
        $chartDataA4 = [
            ['Chuan Dau Ra', 'Giỏi', 'Khá', 'Trung Bình', 'Yếu'],
            ['G2.2', $resultStatsA4['G2.2']['gioi'], $resultStatsA4['G2.2']['kha'], $resultStatsA4['G2.2']['trungbinh'], $resultStatsA4['G2.2']['yeu']],
            ['G3.1', $resultStatsA4['G3.1']['gioi'], $resultStatsA4['G3.1']['kha'], $resultStatsA4['G3.1']['trungbinh'], $resultStatsA4['G3.1']['yeu']],
            ['G3.2', $resultStatsA4['G3.2']['gioi'], $resultStatsA4['G3.2']['kha'], $resultStatsA4['G3.2']['trungbinh'], $resultStatsA4['G3.2']['yeu']],
            ['G6.1', $resultStatsA4['G6.1']['gioi'], $resultStatsA4['G6.1']['kha'], $resultStatsA4['G6.1']['trungbinh'], $resultStatsA4['G6.1']['yeu']],
        ];

        // Trả về view với dữ liệu thống kê
        return view('teacher.view.statics', compact('class', 'scoreStatistics', 'baiKiemTras', 'leaderboard', 'chartData', 'chartDataA3', 'chartDataA4'));
    }

    public function classScores($malop)
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
            return redirect()->route('teacher.classlist')->withErrors(['error' => 'Lớp không tồn tại']);
        }

        // Lấy danh sách sinh viên qua QuanLyHS
        $members = QuanLyHS::where('malop', $malop)
            ->with('sinhVien')
            ->get();

        // Lấy thông tin bài kiểm tra trong lớp
        $baiKiemTras = BaiKiemTra::where('malop', $malop)->get();

        // Tạo danh sách sinh viên kèm điểm cao nhất từng bài kiểm tra
        $studentsWithResults = $members->map(function ($member) use ($baiKiemTras) {
            $student = $member->sinhVien;

            $results = $baiKiemTras->map(function ($baiKiemTra) use ($student) {
                $highestScore = KetQuaBaiKiemTra::where('msbkt', $baiKiemTra->msbkt)
                    ->where('mssv', $student->mssv)
                    ->max('diem'); // Lấy điểm cao nhất

                return [
                    'bai_kiem_tra' => $baiKiemTra->tenbkt,
                    'diem' => $highestScore !== null ? $highestScore : '-'
                ];
            });

            return [
                'sinh_vien' => $student,
                'ket_qua' => $results
            ];
        });

        return view('teacher.view.scores', compact('class', 'studentsWithResults', 'baiKiemTras'));
    }


    public function addLecture(Request $request, $malop)
    {
        // Lấy thông tin lớp học từ 'malop'
        $user = Session::get('user');
        $class = LopHoc::where('malop', $malop)
            ->with(['quanLyHS', 'quanLyGV', 'baiGiang', 'baiKiemTra'])
            ->first();

        if (!$class) {
            return redirect()->route('teacher.classlist')->withErrors(['error' => 'Lớp không tồn tại']);
        }

        // Kiểm tra nếu form được submit
        if ($request->isMethod('post')) {
            // Xử lý upload file
            $filePaths = [];
            $linkPaths = $request->input('link_paths') ?? null; // Gán null nếu không có input

            if ($request->hasFile('file_paths')) {
                $files = $request->file('file_paths');

                // Tạo thư mục với msbg
                $msbg = BaiGiang::max('msbg') ?? 0; // Trả về 0 nếu không có bản ghi nào
                $msbg += 1;

                $folderPath = public_path('uploads/' . $msbg);
                if (!File::exists($folderPath)) {
                    File::makeDirectory($folderPath, 0777, true);
                }

                foreach ($files as $file) {
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $filePath = $file->move($folderPath, $fileName);
                    $filePaths[] = 'uploads/' . $msbg . '/' . $fileName; // Lưu đường dẫn file vào mảng
                }
            }

            // Lưu bài giảng vào database
            $baiGiang = new BaiGiang([
                'tenbg' => $request->input('tenbg'),
                'noidungbg' => $request->input('noidungbg'),
                'file_paths' => json_encode($filePaths), // Lưu dưới dạng JSON nếu có nhiều file
                'link_paths' => $linkPaths,
                'malop' => $malop,
            ]);

            $baiGiang->save();

            // Chuyển hướng đến trang lectures
            return redirect()->route('class.lectures', ['malop' => $malop])->with('alert', 'Thêm bài giảng thành công');
        }

        return view('teacher.add.lecture', compact('class'));
    }


    public function testType($malop)
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
            return redirect()->route('teacher.classlist')->withErrors(['error' => 'Lớp không tồn tại']);
        }
        return view('teacher.add.test.type', compact('class'));
    }

    public function testPercent($malop)
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
            return redirect()->route('teacher.classlist')->withErrors(['error' => 'Lớp không tồn tại']);
        }
        $tests = $class->baiKiemTra;

        return view('teacher.add.test.percent', compact('class', 'tests'));
    }

    public function testForm($malop)
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
            return redirect()->route('teacher.classlist')->withErrors(['error' => 'Lớp không tồn tại']);
        }
        return view('teacher.add.test.form', compact('class', 'malop'));
    }

    public function testEssay($malop)
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
            return redirect()->route('teacher.classlist')->withErrors(['error' => 'Lớp không tồn tại']);
        }
        return view('teacher.add.test.essay', compact('class', 'malop'));
    }

    public function deleteLecture($malop, $id)
    {
        $lecture = BaiGiang::where('malop', $malop)->where('msbg', $id)->first();

        if ($lecture) {
            // Đường dẫn thư mục bài giảng trong thư mục public/uploads
            $lectureFolder = public_path('uploads/' . $lecture->msbg); // Đảm bảo đường dẫn chính xác đến thư mục

            // Kiểm tra và xóa thư mục nếu nó tồn tại
            if (File::exists($lectureFolder)) {
                // Xóa toàn bộ thư mục và các tệp bên trong
                File::deleteDirectory($lectureFolder);
            }

            // Xóa bài giảng khỏi cơ sở dữ liệu
            $lecture->delete();

            // Quay lại trang danh sách bài giảng với thông báo thành công
            return redirect()->route('class.lectures', ['malop' => $malop])->with('alert', 'Xóa bài giảng và thư mục thành công');
        }

        // Nếu không tìm thấy bài giảng, hiển thị lỗi
        return redirect()->route('class.lectures', ['malop' => $malop])->withErrors(['error' => 'Bài giảng không tồn tại']);
    }

    // Lưu bài tập (trắc nghiệm)
    public function storetest(Request $request, $malop)
    {
        // Validation form
        $validated = $request->validate([
            'tenbkt' => 'required|string|max:255',
            'date-start' => 'required|date',
            'date-end' => 'required|date|after_or_equal:date-start',
            'time-doing' => 'required|integer|min:1',
            'times-allow' => 'required|integer|min:1',
            'file-input' => 'nullable|file|mimes:jpg,jpeg,png,pdf,docx,doc|max:10240', // Tệp có thể lên đến 10MB
            'num-questions' => 'required|integer|min:1',
            'tpdg' => 'required|string',
            // Kiểm tra câu trả lời và chuẩn đầu ra cho các câu hỏi
            'answer-*' => 'required|string', // Kiểm tra câu trả lời cho tất cả các câu hỏi
            'cdr-*' => 'required|string',    // Kiểm tra chuẩn đầu ra cho tất cả các câu hỏi
            'points-*' => 'required|numeric|min:0', // Kiểm tra điểm cho các câu hỏi
        ]);

        try {
            $filePath = null;

            $msbkt = BaiKiemTra::max('msbkt') ?? 0; // Trả về 0 nếu không có bản ghi nào
            $msbkt += 1;

            if ($request->hasFile('file-input')) {
                $file = $request->file('file-input');
                $folderPath = public_path('test/' . $msbkt);
                if (!File::exists($folderPath)) {
                    File::makeDirectory($folderPath, 0777, true);
                }

                $filePath = 'test/' . $msbkt . '/' . $file->getClientOriginalName();
                $file->move($folderPath, $file->getClientOriginalName());
            }

            $baiKiemTra = new BaiKiemTra([
                'tenbkt' => $request->input('tenbkt'),
                'ngaybatdau' => $request->input('date-start'),
                'ngayketthuc' => $request->input('date-end'),
                'thoigianlambai' => $request->input('time-doing'),
                'danhgia_id' => ThanhPhanDanhGia::where('thanhphan', $request->input('tpdg'))->first()->id,
                'malop' => $malop,
                'loai_bkt' => 'TracNghiem',
                'num_ques' => $request->input('num-questions'),
                'solanlam' => $request->input('times-allow'),
                'file_path' => $filePath,
            ]);

            $baiKiemTra->save();
            $msbkt = $baiKiemTra->msbkt;

            $numQuestions = $request->input('num-questions');
            for ($i = 1; $i <= $numQuestions; $i++) {
                $cauHoi = new CauHoi([
                    'chuan_id' => $request->input("cdr-$i"), // Chuẩn đầu ra
                    'dapan' => $request->input("answer-$i"), // Câu trả lời
                    'diem' => $request->input("points-$i"), // Điểm cho câu hỏi
                    'msbkt' => $msbkt,
                ]);

                $cauHoi->save();
            }

            return redirect()->route('class.tests', ['malop' => $malop])->with('alert', 'Thêm bài tập thành công');
        } catch (Exception $e) {
            return redirect()->back()->with('alert', 'Đã có lỗi xảy ra khi lưu bài kiểm tra. Vui lòng thử lại sau.')->withInput();
        }
    }

    // Xóa bài tập
    public function deleteTest($malop, $msbkt)
    {
        try {
            // Tìm bài kiểm tra theo mã lớp và mã bài kiểm tra
            $baiKiemTra = BaiKiemTra::where('malop', $malop)->where('msbkt', $msbkt)->first();
            if (!$baiKiemTra) {
                return redirect()->route('class.tests', ['malop' => $malop])->with('alert', 'Bài kiểm tra không tồn tại.');
            }

            // Kiểm tra xem trong bảng KetQuaBaiKiemTra đã có bản ghi nào chứa msbkt chưa
            $hasScores = KetQuaBaiKiemTra::where('msbkt', $msbkt)->exists();

            // Nếu bài kiểm tra đã có điểm (tức là sinh viên đã nộp bài), không cho phép xóa
            if ($hasScores) {
                return redirect()->route('class.tests', ['malop' => $malop])->with('alert', 'Không thể xóa bài kiểm tra vì đã có sinh viên nộp bài.');
            }

            // Tiến hành xóa câu hỏi liên quan đến bài kiểm tra chỉ khi chưa có sinh viên nộp bài
            CauHoi::where('msbkt', $msbkt)->delete();

            // Xóa file bài kiểm tra trong thư mục nếu có
            if ($baiKiemTra->file_path && File::exists(public_path($baiKiemTra->file_path))) {
                File::delete(public_path($baiKiemTra->file_path));
            }

            // Xóa thư mục chứa bài kiểm tra (nếu cần)
            $folderPath = public_path('test/' . $msbkt);

            // Kiểm tra nếu thư mục tồn tại
            if (File::exists($folderPath)) {
                // Xóa toàn bộ thư mục và các file bên trong
                File::deleteDirectory($folderPath);
            }

            // Xóa bài kiểm tra
            $baiKiemTra->delete();

            // Trả về thông báo thành công
            return redirect()->route('class.tests', ['malop' => $malop])->with('alert', 'Xóa bài kiểm tra thành công.');
        } catch (Exception $e) {
            // Nếu xảy ra lỗi, trả về thông báo lỗi
            return redirect()->route('class.tests', ['malop' => $malop])->with('alert', 'Đã có lỗi xảy ra khi xóa bài kiểm tra. Vui lòng thử lại sau.');
        }
    }


    // Đếm số lượng chuẩn đầu ra
    public function getCdr(Request $request)
    {
        $tpdgId = $request->query('tpdg');
        if (!$tpdgId) {
            return response()->json(['error' => 'Thiếu TPDG ID.'], 400);
        }

        $cdrs = ChuanDauRa::where('tpdg_id', $tpdgId)->get();

        if ($cdrs->isEmpty()) {
            return response()->json(['cdrs' => [], 'message' => 'Không tìm thấy chuẩn đầu ra.'], 200);
        }

        return response()->json(['cdrs' => $cdrs, 'count' => $cdrs->count()], 200);
    }

    // Lưu bài tập (tự luận)
    public function storetestEssay(Request $request, $malop)
    {

        // Validation form
        $validated = $request->validate([
            'tenbkt' => 'required|string|max:255',
            'date-start' => 'required|date',
            'date-end' => 'required|date|after_or_equal:date-start',
            'file-input' => 'nullable|file|mimes:jpg,jpeg,png,pdf,docx,doc|max:10240', // Tệp có thể lên đến 10MB
            'tpdg' => 'required|string',
            // Kiểm tra câu trả lời và chuẩn đầu ra cho các câu hỏi
            'answer-*' => 'required|string',
            'cdr-*' => 'required|string',
            'points-*' => 'required|numeric|min:0',
        ]);

        try {
            $filePath = null;

            $msbkt = BaiKiemTra::max('msbkt') ?? 0; // Trả về 0 nếu không có bản ghi nào
            $msbkt += 1;

            if ($request->hasFile('file-input')) {
                $file = $request->file('file-input');
                $folderPath = public_path('test/' . $msbkt);
                if (!File::exists($folderPath)) {
                    File::makeDirectory($folderPath, 0777, true);
                }

                $filePath = 'test/' . $msbkt . '/' . $file->getClientOriginalName();
                $file->move($folderPath, $file->getClientOriginalName());
            }

            $baiKiemTra = new BaiKiemTra([
                'tenbkt' => $request->input('tenbkt'),
                'ngaybatdau' => $request->input('date-start'),
                'ngayketthuc' => $request->input('date-end'),
                'thoigianlambai' => null,
                'danhgia_id' => ThanhPhanDanhGia::where('thanhphan', $request->input('tpdg'))->first()->id,
                'malop' => $malop,
                'loai_bkt' => 'TuLuan',
                'num_ques' => null,
                'solanlam' => null,
                'file_path' => $filePath,
                'diem' => null,
                'loinhanxet' => null,
            ]);

            $baiKiemTra->save();
            $msbkt = $baiKiemTra->msbkt;

            $numQuestions = $request->input('num-questions');
            for ($i = 1; $i <= $numQuestions; $i++) {
                $cauHoi = new CauHoi([
                    'chuan_id' => $request->input(key: "cdr-$i"),
                    'dapan' => null,
                    'diem' => $request->input("points-$i"),
                    'msbkt' => $msbkt,
                ]);
                $cauHoi->save();
            }

            return redirect()->route('class.tests', ['malop' => $malop])->with('alert', 'Thêm bài tập thành công');
        } catch (Exception $e) {
            return redirect()->back()->with('alert', 'Đã có lỗi xảy ra khi lưu bài kiểm tra. Vui lòng thử lại sau.')->withInput();
        }
    }

    public function showLecture($id, $malop)
    {
        // Tìm bài giảng theo ID
        $lecture = BaiGiang::findOrFail($id);
        $user = Session::get('user');

        // Kiểm tra lớp học tồn tại
        $class = LopHoc::where('malop', $malop)
            ->with([
                'quanLyHS',
                'quanLyGV',
                'baiGiang',
                'baiKiemTra'
            ])
            ->first();

        // Trả về view chi tiết bài giảng
        return view('teacher.detail.lecture', compact('lecture', 'class'));
    }

    public function showUpdateLectureForm($id, $malop)
    {
        $lecture = BaiGiang::where('msbg', $id)->first();
        $class = LopHoc::where('malop', $malop)->first();

        return view('teacher.update.lecture', compact('lecture', 'class'));
    }

    public function updateLecture(Request $request, $id, $malop)
    {
        // Lấy thông tin lớp học
        $class = LopHoc::where('malop', $malop)->first();
        if (!$class) {
            return redirect()->route('teacher.classlist')->withErrors(['error' => 'Lớp không tồn tại']);
        }

        // Lấy bài giảng cần cập nhật
        $lecture = BaiGiang::where('msbg', $id)->first();
        if (!$lecture) {
            return redirect()->route('teacher.classlist')->withErrors(['error' => 'Bài giảng không tồn tại']);
        }

        if ($request->isMethod('post')) {
            $linkPaths = $request->input('link_paths') ?? null;
            $filePaths = [];

            // Xử lý upload file
            if ($request->hasFile('file_paths')) {
                $files = $request->file('file_paths');
                $folderPath = public_path('uploads/' . $lecture->msbg);
                if (!File::exists($folderPath)) {
                    File::makeDirectory($folderPath, 0777, true);
                }

                foreach ($files as $file) {
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $filePath = $file->move($folderPath, $fileName);
                    $filePaths[] = 'uploads/' . $lecture->msbg . '/' . $fileName;
                }
            }

            // Cập nhật thông tin bài giảng
            $lecture->update([
                'tenbg' => $request->input('tenbg'),
                'noidungbg' => $request->input('noidungbg'),
                'link_paths' => $linkPaths, // Lưu link nếu có
                'file_paths' => !empty($filePaths) ? json_encode($filePaths) : $lecture->file_paths, // If no file is uploaded, keep the existing value
            ]);

            return redirect()->route('class.lectures', ['malop' => $malop])->with('alert', 'Cập nhật bài giảng thành công');
        }

        return view('teacher.update.lecture', compact('lecture', 'class'));
    }

    public function gradingList($malop, $msbkt)
    {
        $class = LopHoc::where('malop', $malop)
            ->with(['quanLyHS.sinhVien'])
            ->first();

        if (!$class) {
            return redirect()->route('teacher.classlist')->withErrors(['error' => 'Lớp không tồn tại']);
        }

        $test = BaiKiemTra::where('msbkt', $msbkt)->first();

        if (!$test) {
            return redirect()->route('teacher.classlist')->withErrors(['error' => 'Bài kiểm tra không tồn tại']);
        }

        $students = $class->quanLyHS->map(function ($qlhs) use ($msbkt) {
            $student = $qlhs->sinhVien;
            $result = KetQuaBaiKiemTra::where('msbkt', $msbkt)
                ->where('mssv', $student->mssv)
                ->first();

            return [
                'name' => $student->tensv,
                'id' => $student->mssv,
                'status' => $result && $result->files_path ? 'Đã làm' : 'Chưa làm',
                'score' => $result ? $result->diem : '-',
            ];
        });

        return view('teacher.grading.list', compact('test', 'class', 'students'));
    }

    public function gradingStudent($malop, $msbkt, $mssv)
    {
        $class = LopHoc::where('malop', $malop)->first();
        if (!$class) {
            return redirect()->route('teacher.classlist')->withErrors(['error' => 'Lớp không tồn tại']);
        }

        $test = BaiKiemTra::where('msbkt', $msbkt)->first();
        if (!$test) {
            return redirect()->route('teacher.classlist')->withErrors(['error' => 'Bài kiểm tra không tồn tại']);
        }

        $student = SinhVien::where('mssv', $mssv)->first();
        if (!$student) {
            return redirect()->route('teacher.classlist')->withErrors(['error' => 'Sinh viên không tồn tại']);
        }

        $outcomes = CauHoi::join('ChuanDauRa', 'CauHoi.chuan_id', '=', 'ChuanDauRa.id')
            ->where('CauHoi.msbkt', $msbkt)
            ->select(
                'ChuanDauRa.chuan as outcome_name',
                'CauHoi.diem as predefined_point',
                'CauHoi.msch as question_id'
            )
            ->get();

        // Lấy ra file_path từ bảng KetQuaBaiKiemTra cho mssv và msbkt
        $result = KetQuaBaiKiemTra::where('msbkt', $msbkt)
            ->where('mssv', $mssv)
            ->first();

        // Nếu có file_path, truyền vào view
        $filePath = $result ? $result->files_path : null;
        return view('teacher.grading.student', compact('class', 'test', 'student', 'outcomes', 'filePath', 'msbkt'));
    }

    public function submitGrading(Request $request)
    {
        // Validate các thông tin đầu vào
        $validated = $request->validate([
            'msbkt' => 'required|exists:BaiKiemTra,msbkt',
            'mssv' => 'required|exists:SinhVien,mssv',
            'points' => 'array|required',
            'points.*' => 'numeric|min:0|max:10',
            'comment' => 'nullable|string|max:500',
        ]);

        // Tìm hoặc tạo bản ghi trong bảng SinhVienKetQua
        $sinhvienKetQua = SinhVienKetQua::firstOrCreate(
            [
                'msbkt' => $validated['msbkt'],
                'mssv' => $validated['mssv'],
                'malop' => $request->input('malop'),
            ],
            [
                'updated_at' => now(),
            ]
        );

        $sinhvien_ketqua_id = $sinhvienKetQua->id;
        $totalPoints = 0;

        foreach ($validated['points'] as $question_id => $point) {
            $cauHoi = CauHoi::find($question_id);
            if (!$cauHoi) {
                return redirect()->back()->withErrors("Không tìm thấy câu hỏi với ID: $question_id");
            }

            $chuan_id = $cauHoi->chuan_id;

            KetQuaChuans::updateOrCreate(
                [
                    'sinhvien_ketqua_id' => $sinhvien_ketqua_id,
                    'chuan_id' => $chuan_id,
                ],
                [
                    'so_cau_dung' => $point,
                    'updated_at' => now(),
                ]
            );
            $totalPoints += $point;
        }

        $ketqua = KetQuaBaiKiemTra::updateOrCreate(
            [
                'msbkt' => $validated['msbkt'],
                'mssv' => $validated['mssv'],
            ],
            [
                'diem' => $totalPoints,
                'updated_at' => now(),
            ]
        );

        $nhanXet = NhanXetBaiKiemTra::where('ketqua_id', $ketqua->id)
            ->where('msgv', Session::get('user.id'))
            ->first();

        if ($nhanXet) {
            $nhanXet->update([
                'nhanxet' => $request->input('comment'),
                'thoigian' => now(),
            ]);
        } else {
            NhanXetBaiKiemTra::create([
                'ketqua_id' => $ketqua->id,
                'msgv' => Session::get('user.id'),
                'nhanxet' => $request->input('comment'),
                'thoigian' => now(),
            ]);
        }

        return redirect()->route('grading.list', [
            'malop' => $request->input('malop'),
            'msbkt' => $validated['msbkt'],
        ])->with([
            'alert' => 'Điểm đã được cập nhật thành công!',
        ]);
    }

    public function storeTestPercent(Request $request, $malop)
    {
        $request->validate([
            'tile.*' => 'required|numeric|min:0|max:100',
        ], [
            'tile.*.required' => 'Vui lòng nhập tỉ lệ.',
            'tile.*.numeric' => 'Tỉ lệ phải là một số.',
            'tile.*.min' => 'Tỉ lệ không được nhỏ hơn 0.',
            'tile.*.max' => 'Tỉ lệ không được lớn hơn 100.',
        ]);

        $tiles = $request->input('tile');

        $class = LopHoc::where('malop', $malop)->with('baiKiemTra')->first();
        if (!$class) {
            return redirect()->route('teacher.classlist')->withErrors(['error' => 'Lớp không tồn tại']);
        }

        $tests = $class->baiKiemTra;

        // Kiểm tra tổng tỉ lệ theo từng TPDG
        $groupedByDanhGia = [];
        foreach ($tests as $test) {
            $groupedByDanhGia[$test->danhgia_id][] = $test->msbkt;
        }

        foreach ($groupedByDanhGia as $danhgiaId => $testIds) {
            $totalPercent = 0;
            foreach ($testIds as $testId) {
                $totalPercent += $tiles[$testId] ?? 0;
            }
            if ($totalPercent !== 100) {
                return redirect()->back()->withInput()->with('alert', "Tổng tỉ lệ của thành phần đánh giá $danhgiaId phải bằng 100%.");
            }
        }

        foreach ($tiles as $key => $tile) {
            $bkt = BaiKiemTra::findOrFail($key);
            BaiKiemTraTiLe::updateOrCreate(
                ['msbkt' => $bkt->msbkt, 'malop' => $malop],
                ['tile' => $tile]
            );
        }

        return redirect()->route('class.tests', ['malop' => $malop])->with('alert', 'Lưu tỉ lệ thành công.');
    }

    public function editEssay($id, $malop)
    {
        $baiKiemTra = BaiKiemTra::findOrFail($id);
        $thanhPhanDanhGia = ThanhPhanDanhGia::all();
        $cauHoi = CauHoi::where('msbkt', $id)->get();

        return view('teacher.update.test.essay', [
            'baiKiemTra' => $baiKiemTra,
            'thanhPhanDanhGia' => $thanhPhanDanhGia,
            'malop' => $malop,
            'cauHoi' => $cauHoi
        ]);
    }

    public function editForm($id, $malop)
    {
        $baiKiemTra = BaiKiemTra::findOrFail($id);
        $thanhPhanDanhGia = ThanhPhanDanhGia::all();
        $cauHoi = CauHoi::where('msbkt', $id)->get();

        return view('teacher.update.test.form', [
            'baiKiemTra' => $baiKiemTra,
            'thanhPhanDanhGia' => $thanhPhanDanhGia,
            'malop' => $malop,
            'cauHoi' => $cauHoi
        ]);
    }

    public function updateTest(Request $request, $id, $malop)
    {
        $validated = $request->validate([
            'tenbkt' => 'required|string|max:255',
            'date-start' => 'required|date',
            'date-end' => 'required|date|after_or_equal:date-start',
            'time-doing' => 'required|integer|min:1',
            'times-allow' => 'required|integer|min:1',
            'file-input' => 'nullable|file|mimes:jpg,jpeg,png,pdf,docx,doc|max:10240',
            'tpdg' => 'required|string',
            // Kiểm tra câu trả lời và chuẩn đầu ra cho các câu hỏi
            'answer-*' => 'required|string',
            'cdr-*' => 'required|string',
            'points-*' => 'required|numeric|min:0',
        ]);

        try {
            $baiKiemTra = BaiKiemTra::findOrFail($id);
            $filePath = $baiKiemTra->file_path; // Giữ nguyên đường dẫn file cũ nếu không cập nhật

            // Xử lý file mới (nếu có)
            if ($request->hasFile('file-input')) {
                $file = $request->file('file-input');
                $folderPath = public_path('test/' . $id);
                if (!File::exists($folderPath)) {
                    File::makeDirectory($folderPath, 0777, true);
                }

                $filePath = 'test/' . $id . '/' . $file->getClientOriginalName();
                $file->move($folderPath, $file->getClientOriginalName());
            }

            // Cập nhật thông tin bài kiểm tra
            $baiKiemTra->update([
                'tenbkt' => $request->input('tenbkt'),
                'ngaybatdau' => $request->input('date-start'),
                'ngayketthuc' => $request->input('date-end'),
                'thoigianlambai' => $request->input('time-doing'),
                'danhgia_id' => ThanhPhanDanhGia::where('thanhphan', $request->input('tpdg'))->first()->id,
                'solanlam' => $request->input('times-allow'),
                'file_path' => $filePath,
            ]);

            // Cập nhật lại các câu hỏi của bài kiểm tra
            $numQuestions = $request->input('num-questions');

            // Lấy msch nhỏ nhất cho bài kiểm tra hiện tại
            $minMsch = CauHoi::where('msbkt', $id)->min('msch') ?? 1;

            // Giá trị msch bắt đầu
            $startMsch = $minMsch;

            // Cập nhật hoặc tạo câu hỏi mới
            for ($i = 0; $i < $numQuestions; $i++) {
                // Tính toán giá trị msch cho câu hỏi hiện tại
                $currentMsch = $startMsch + $i;

                // Tìm câu hỏi theo msch và bài kiểm tra
                $cauHoi = CauHoi::where('msbkt', $id)
                    ->where('msch', $currentMsch)
                    ->first();

                if ($cauHoi) {
                    // Nếu câu hỏi đã tồn tại, cập nhật
                    $cauHoi->update([
                        'chuan_id' => $request->input("cdr-" . ($i + 1)),
                        'dapan' => $request->input("answer-" . ($i + 1)),
                        'diem' => $request->input("points-" . ($i + 1)),
                    ]);
                } else {
                    // Nếu câu hỏi không tồn tại, tạo mới
                    CauHoi::create([
                        'msbkt' => $id,
                        'msch' => $currentMsch,
                        'chuan_id' => $request->input("cdr-" . ($i + 1)),
                        'dapan' => $request->input("answer-" . ($i + 1)),
                        'diem' => $request->input("points-" . ($i + 1)),
                    ]);
                }
            }

            // Cập nhật điểm lại cho sinh viên
            $this->updateStudentScores($id, $malop);

            return redirect()->route('class.tests', ['malop' => $malop])->with('alert', 'Cập nhật bài kiểm tra thành công');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Đã có lỗi xảy ra khi cập nhật bài kiểm tra.')->withInput();
        }
    }

    public function updateStudentScores($msbkt, $malop)
    {
        // Lấy danh sách sinh viên đã làm bài kiểm tra
        $sinhVienKetQuaList = SinhVienKetQua::where('malop', $malop)
            ->where('msbkt', $msbkt)
            ->get();

        // Lấy danh sách câu hỏi trong bài kiểm tra
        $cauHoiList = CauHoi::where('msbkt', $msbkt)->get();

        foreach ($sinhVienKetQuaList as $sinhVienKetQua) {
            $mssv = $sinhVienKetQua->mssv;

            // Lấy kết quả làm bài của sinh viên
            $ketQuaBaiKiemTra = KetQuaBaiKiemTra::where('msbkt', $msbkt)
                ->where('mssv', $mssv)
                ->first();

            if (!$ketQuaBaiKiemTra) {
                // Nếu không có kết quả bài kiểm tra, bỏ qua sinh viên này
                continue;
            }

            // Lấy câu trả lời của sinh viên
            $finalAnswers = json_decode($ketQuaBaiKiemTra->cau_tra_loi, true);

            $tongDiem = 0;
            $soCauDungTheoChuan = [];

            // Tính điểm và số câu đúng theo từng chuẩn
            foreach ($cauHoiList as $cauHoi) {
                $answer = $finalAnswers[$cauHoi->msch] ?? null;

                if ($answer !== null && trim($answer) === trim($cauHoi->dapan)) {
                    $tongDiem += $cauHoi->diem;

                    if (!isset($soCauDungTheoChuan[$cauHoi->chuan_id])) {
                        $soCauDungTheoChuan[$cauHoi->chuan_id] = 0;
                    }
                    $soCauDungTheoChuan[$cauHoi->chuan_id]++;
                }
            }

            // Cập nhật điểm tổng cho sinh viên
            $ketQuaBaiKiemTra->update(['diem' => $tongDiem]);

            // Cập nhật số câu đúng theo từng chuẩn
            foreach ($soCauDungTheoChuan as $chuanId => $soCauDung) {
                KetQuaChuans::updateOrCreate(
                    [
                        'sinhvien_ketqua_id' => $sinhVienKetQua->id,
                        'chuan_id' => $chuanId,
                    ],
                    ['so_cau_dung' => $soCauDung]
                );
            }
        }
    }


    public function updateEssay(Request $request, $id, $malop)
    {
        $validated = $request->validate([
            'tenbkt' => 'required|string|max:255',
            'date-start' => 'required|date',
            'date-end' => 'required|date|after_or_equal:date-start',
            'file-input' => 'nullable|file|mimes:jpg,jpeg,png,pdf,docx,doc|max:10240',
            // Kiểm tra câu trả lời và chuẩn đầu ra cho các câu hỏi
            'answer-*' => 'required|string',
            'cdr-*' => 'required|string',
            'points-*' => 'required|numeric|min:0',
        ]);

        try {
            $baiKiemTra = BaiKiemTra::findOrFail($id);
            $filePath = $baiKiemTra->file_path; // Giữ nguyên đường dẫn file cũ nếu không cập nhật

            // Xử lý file mới (nếu có)
            if ($request->hasFile('file-input')) {
                $file = $request->file('file-input');
                $folderPath = public_path('test/' . $id);
                if (!File::exists($folderPath)) {
                    File::makeDirectory($folderPath, 0777, true);
                }

                $filePath = 'test/' . $id . '/' . $file->getClientOriginalName();
                $file->move($folderPath, $file->getClientOriginalName());
            }

            // Cập nhật thông tin bài kiểm tra
            $baiKiemTra->update([
                'tenbkt' => $request->input('tenbkt'),
                'ngaybatdau' => $request->input('date-start'),
                'ngayketthuc' => $request->input('date-end'),
                'file_path' => $filePath,
            ]);

            $cauHoiList = CauHoi::where('msbkt', $id)->get();

            // Kiểm tra nếu có câu hỏi cần cập nhật
            foreach ($cauHoiList as $index => $cauHoi) {
                // Tạo tên cho input điểm dựa trên thứ tự câu hỏi
                $pointKey = "points-" . ($index + 1);  // Giả sử input sẽ có tên points-1, points-2, ...

                // Kiểm tra nếu có giá trị điểm trong request
                if ($request->has($pointKey)) {
                    // Cập nhật điểm cho câu hỏi
                    $cauHoi->diem = $request->input($pointKey);
                    $cauHoi->save();  // Lưu lại thay đổi
                }
            }

            return redirect()->route('class.tests', ['malop' => $malop])->with('alert', 'Cập nhật bài kiểm tra thành công');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Đã có lỗi xảy ra khi cập nhật bài kiểm tra.')->withInput();
        }
    }
}
