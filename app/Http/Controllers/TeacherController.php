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
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    public function teacherDashboard()
    {
        return view('teacher.dashboard');
    }

    public function classList()
    {
        $user = Session::get('user');

        if (!$user || $user['role'] !== 'giaovien') {
            return redirect()->route('login')->withErrors(['error' => 'Bạn không có quyền truy cập']);
        }

        // Truy vấn lớp học mà giáo viên quản lý
        $classes = LopHoc::whereHas('quanLyGV', function ($query) use ($user) {
            $query->where('msgv', $user['id']);
        })
            ->withCount([
                'quanLyHS as so_hoc_sinh', // Đếm số học sinh
                'baiGiang as so_bai_giang', // Đếm số bài giảng
                'baiKiemTra as so_bai_kiem_tra', // Đếm số bài kiểm tra
            ])
            ->get();

        return view('teacher.classlist', compact('classes'));
    }


    public function teacherProfile()
    {
        $user = Session::get('user');
        return view('teacher.profile', compact('user'));
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

        return redirect()->route('teacher.password')->with('success', 'Mật khẩu đã được thay đổi thành công.');
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

        return view('teacher.view.classes', compact('class'));
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

        return view('teacher.view.tests', compact('class', 'tests'));
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

        return view('teacher.view.statics', compact('class'));
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
            return redirect()->route('class.lectures', ['malop' => $malop])->with('success', 'Thêm bài giảng thành công');
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

    public function updateLecture($malop)
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
        return view('teacher.update.lecture', compact('class'));
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
            return redirect()->route('class.lectures', ['malop' => $malop])->with('success', 'Xóa bài giảng và thư mục thành công');
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
            // Lưu file vào thư mục public/test/{msbkt}
            $filePath = null;

            $msbkt = BaiKiemTra::max('msbkt') ?? 0; // Trả về 0 nếu không có bản ghi nào
            $msbkt += 1;

            if ($request->hasFile('file-input')) {
                $file = $request->file('file-input');
                // Tạo msbkt (có thể sử dụng msbkt tự động từ database sau)
                $folderPath = public_path('test/' . $msbkt);
                if (!File::exists($folderPath)) {
                    File::makeDirectory($folderPath, 0777, true);
                }

                // Lưu tệp vào thư mục tương ứng
                $filePath = 'test/' . $msbkt . '/' . $file->getClientOriginalName();
                $file->move($folderPath, $file->getClientOriginalName());
            }

            // Tạo mới bài kiểm tra
            $baiKiemTra = new BaiKiemTra([
                'tenbkt' => $request->input('tenbkt'),
                'ngaybatdau' => $request->input('date-start'),
                'ngayketthuc' => $request->input('date-end'),
                'thoigianlambai' => $request->input('time-doing'),
                'danhgia_id' => ThanhPhanDanhGia::where('thanhphan', $request->input('tpdg'))->first()->id,
                'malop' => $malop,
                'loai_bkt' => 'TracNghiem',  // Mặc định là trắc nghiệm
                'num_ques' => $request->input('num-questions'),
                'solanlam' => $request->input('times-allow'),
                'file_path' => $filePath, // Lưu đường dẫn file
            ]);

            // Lưu bài kiểm tra
            $baiKiemTra->save();
            $msbkt = $baiKiemTra->msbkt;

            // Lưu danh sách câu hỏi
            $numQuestions = $request->input('num-questions');
            for ($i = 1; $i <= $numQuestions; $i++) {
                // Lưu câu hỏi
                $cauHoi = new CauHoi([
                    'chuan_id' => $request->input("cdr-$i"), // Chuẩn đầu ra
                    'dapan' => $request->input("answer-$i"), // Câu trả lời
                    'diem' => $request->input("points-$i"), // Điểm cho câu hỏi
                    'msbkt' => $msbkt,
                ]);

                // Lưu câu hỏi
                $cauHoi->save();
            }

            // Thông báo thành công
            return redirect()->route('class.tests', ['malop' => $malop])->with('success', 'Thêm bài tập thành công');
        } catch (Exception $e) {
            // Nếu có lỗi, hiện thông báo lỗi
            return redirect()->back()->with('error', 'Đã có lỗi xảy ra khi lưu bài kiểm tra. Vui lòng thử lại sau.')->withInput();
        }
    }

    // Xóa bài tập
    public function deleteTest($malop, $msbkt)
    {
        try {
            // Tìm bài kiểm tra theo mã lớp và mã bài kiểm tra
            $baiKiemTra = BaiKiemTra::where('malop', $malop)->where('msbkt', $msbkt)->first();

            // Nếu không tìm thấy bài kiểm tra, trả về lỗi
            if (!$baiKiemTra) {
                return redirect()->route('class.tests', ['malop' => $malop])->with('error', 'Bài kiểm tra không tồn tại.');
            }

            // Xóa các câu hỏi liên quan đến bài kiểm tra
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
                File::deleteDirectory($folderPath); // Không cần tham số thứ hai nếu muốn xóa tất cả
            }


            // Xóa bài kiểm tra
            $baiKiemTra->delete();

            // Trả về thông báo thành công
            return redirect()->route('class.tests', ['malop' => $malop])->with('success', 'Xóa bài tập thành công.');
        } catch (Exception $e) {
            // Nếu xảy ra lỗi, trả về thông báo lỗi
            return redirect()->route('class.tests', ['malop' => $malop])->with('error', 'Đã có lỗi xảy ra khi xóa bài tập. Vui lòng thử lại sau.');
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
                'loai_bkt' => 'TuLuan',  // Mặc định là tự luận
                'num_ques' => null,
                'solanlam' => null,
                'file_path' => $filePath, // Lưu đường dẫn file
                'diem' => null, // Bạn có thể thêm logic tính điểm nếu cần
                'loinhanxet' => null, // Nếu có thông tin nhận xét thì thêm vào đây
            ]);

            // Lưu bài kiểm tra
            $baiKiemTra->save();
            $msbkt = $baiKiemTra->msbkt;

            // Lưu danh sách câu hỏi
            $numQuestions = $request->input('num-questions');
            for ($i = 1; $i <= $numQuestions; $i++) {
                // Lưu câu hỏi
                $cauHoi = new CauHoi([
                    'chuan_id' => $request->input(key: "cdr-$i"), // Chuẩn đầu ra
                    'dapan' => null, // Câu trả lời
                    'diem' => $request->input("points-$i"), // Điểm cho câu hỏi
                    'msbkt' => $msbkt,
                ]);

                // Lưu câu hỏi
                $cauHoi->save();
            }

            // Thông báo thành công
            return redirect()->route('class.tests', ['malop' => $malop])->with('success', 'Thêm bài tập thành công');
        } catch (Exception $e) {
            // Nếu có lỗi, hiện thông báo lỗi
            return redirect()->back()->with('error', 'Đã có lỗi xảy ra khi lưu bài kiểm tra. Vui lòng thử lại sau.')->withInput();
        }
    }

    public function show($id)
    {
        // Tìm bài giảng theo ID
        $lecture = BaiGiang::findOrFail($id);

        // Trả về view chi tiết bài giảng
        return view('teacher.detail.lecture', compact('lecture'));
    }

    public function gradingList($malop, $msbkt)
    {
        $class = LopHoc::where('malop', $malop)
            ->with(['quanLyHS.sinhVien']) // Lấy danh sách sinh viên trong lớp
            ->first();

        if (!$class) {
            return redirect()->route('teacher.classlist')->withErrors(['error' => 'Lớp không tồn tại']);
        }

        // Tìm bài kiểm tra dựa trên mã số bài kiểm tra
        $test = BaiKiemTra::where('msbkt', $msbkt)->first();

        if (!$test) {
            return redirect()->route('teacher.classlist')->withErrors(['error' => 'Bài kiểm tra không tồn tại']);
        }

        // Lấy danh sách sinh viên trong lớp
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

        // Lấy danh sách chuẩn đầu ra của bài kiểm tra và điểm đã quy định
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
        return view('teacher.grading.student', compact('class', 'test', 'student', 'outcomes', 'filePath'));
    }

    public function submitGrading(Request $request)
    {
        // Validate các thông tin đầu vào
        $validated = $request->validate([
            'msbkt' => 'required|exists:BaiKiemTra,msbkt',
            'mssv' => 'required|exists:SinhVien,mssv',
            'points' => 'array|required',
            'points.*' => 'numeric|min:0|max:10', // Giới hạn điểm từ 0-10
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

        // Lấy ID của sinh viên kết quả
        $sinhvien_ketqua_id = $sinhvienKetQua->id;

        // Biến lưu tổng điểm
        $totalPoints = 0;

        // Lặp qua các điểm của câu hỏi
        foreach ($validated['points'] as $question_id => $point) {
            // Lấy thông tin câu hỏi để xác định chuẩn đầu ra
            $cauHoi = CauHoi::find($question_id);
            if (!$cauHoi) {
                return redirect()->back()->withErrors("Không tìm thấy câu hỏi với ID: $question_id");
            }

            // Lấy chuẩn đầu ra
            $chuan_id = $cauHoi->chuan_id;

            // Tạo hoặc cập nhật bản ghi trong bảng KetQuaChuans
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

            // Tính tổng điểm
            $totalPoints += $point;
        }

        // Tạo hoặc cập nhật điểm tổng trong bảng KetQuaBaiKiemTra
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

        // Lưu nhận xét vào bảng NhanXetBaiKiemTra
        $nhanXet = NhanXetBaiKiemTra::where('ketqua_id', $ketqua->id)
            ->where('msgv', Session::get('user.id'))
            ->first();

        if ($nhanXet) {
            // Nếu có bản ghi, chỉ cập nhật nhận xét
            $nhanXet->update([
                'nhanxet' => $request->input('comment'),
                'thoigian' => now(), // Cập nhật lại thời gian
            ]);
        } else {
            // Nếu không có bản ghi, tạo mới
            NhanXetBaiKiemTra::create([
                'ketqua_id' => $ketqua->id, // ID từ KetQuaBaiKiemTra
                'msgv' => Session::get('user.id'),
                'nhanxet' => $request->input('comment'),
                'thoigian' => now(), // Thời gian hiện tại
            ]);
        }

        return redirect()->route('grading.list', [
            'malop' => $request->input('malop'),
            'msbkt' => $validated['msbkt'],
        ])->with([
            'success' => 'Điểm đã được cập nhật thành công!',  // Truyền thông báo thành công
        ]);
    }
}
