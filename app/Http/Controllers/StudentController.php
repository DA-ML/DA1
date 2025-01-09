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
use App\Models\NhanXetBaiKiemTra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    protected $currentSemester;
    protected $currentYear;

    // Constructor để khởi tạo giá trị
    public function __construct()
    {
        $this->currentSemester = '1';
        $this->currentYear = '2023-2024';
    }

    public function studentDashboard()
    {
        $user = Session::get('user');
        $student = SinhVien::find($user['id']);

        if (!$student) {
            return back()->with('error', 'Không tìm thấy người dùng.');
        }

        $classes = DB::table('QuanLyHS')
            ->join('LopHoc', 'QuanLyHS.malop', '=', 'LopHoc.malop')
            ->join('HocKy', 'QuanLyHS.mahk', '=', 'HocKy.mahk')
            ->join('Khoa', 'LopHoc.makhoa', '=', 'Khoa.makhoa')
            ->where('QuanLyHS.mssv', $student->mssv)
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

        $lectures = DB::table('QuanLyHS')
            ->join('LopHoc', 'QuanLyHS.malop', '=', 'LopHoc.malop')
            ->join('HocKy', 'QuanLyHS.mahk', '=', 'HocKy.mahk')
            ->leftJoin('BaiGiang', 'LopHoc.malop', '=', 'BaiGiang.malop')
            ->where('QuanLyHS.mssv', $student->mssv)
            ->where('HocKy.tenhk', $this->currentSemester)
            ->where('HocKy.namhoc', $this->currentYear)
            ->select('BaiGiang.tenbg', 'BaiGiang.noidungbg', 'BaiGiang.file_paths', 'BaiGiang.link_paths')
            ->get();

        $exams = DB::table('QuanLyHS')
            ->join('LopHoc', 'QuanLyHS.malop', '=', 'LopHoc.malop')
            ->join('HocKy', 'QuanLyHS.mahk', '=', 'HocKy.mahk')
            ->leftJoin('BaiKiemTra', 'LopHoc.malop', '=', 'BaiKiemTra.malop')
            ->where('QuanLyHS.mssv', $student->mssv)
            ->where('HocKy.tenhk', $this->currentSemester)
            ->where('HocKy.namhoc', $this->currentYear)
            ->select('BaiKiemTra.tenbkt', 'BaiKiemTra.ngaybatdau', 'BaiKiemTra.ngayketthuc', 'BaiKiemTra.file_path', 'BaiKiemTra.loai_bkt', 'BaiKiemTra.num_ques')
            ->get();

        return view('student.dashboard', compact('student', 'classes', 'lectures', 'exams'))->with([
            'currentSemester' => $this->currentSemester,
            'currentYear' => $this->currentYear
        ]);
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

        $mssv = Session::get('user.id');
        if (!$mssv) {
            return redirect()->route('student.classlist')->withErrors(['error' => 'Không tìm thấy MSSV trong session']);
        }

        // A1 - G2.2
        var_name:
        $result = DB::select("
    SELECT
        bkt.msbkt,
        bkt.tenbkt,
        bkt_tile.tile AS tile_bai_kiem_tra,
        tp.thanhphan AS thanhphan_danhgia,
        kqch.chuan_id,
        chuan.chuan AS chuan_dau_ra,
        kqch.so_cau_dung,
        kqbk.diem,
        CASE
            WHEN bkt.loai_bkt = 'TracNghiem' THEN
                (SELECT COUNT(*)
                 FROM CauHoi ch
                 WHERE ch.msbkt = bkt.msbkt AND ch.chuan_id = chuan.id)
            WHEN bkt.loai_bkt = 'TuLuan' THEN
                (SELECT SUM(ch.diem)
                 FROM CauHoi ch
                 WHERE ch.msbkt = bkt.msbkt AND ch.chuan_id = chuan.id)
            ELSE NULL
        END AS ket_qua
    FROM
        BaiKiemTra bkt
    JOIN
        ThanhPhanDanhGia tp ON bkt.danhgia_id = tp.id
    JOIN
        KetQuaBaiKiemTra kqbk ON bkt.msbkt = kqbk.msbkt
    JOIN
        KetQuaChuans kqch ON kqbk.id = kqch.sinhvien_ketqua_id
    JOIN
        ChuanDauRa chuan ON kqch.chuan_id = chuan.id
    JOIN
        BaiKiemTraTile bkt_tile ON bkt.msbkt = bkt_tile.msbkt
    WHERE
        kqbk.mssv = ?  -- Sinh viên (mssv từ URL)
        AND bkt.malop = ?  -- Lớp (malop từ URL)
        AND tp.id = 'A1'
        AND kqbk.diem = (
            SELECT MAX(kqbk2.diem)
            FROM KetQuaBaiKiemTra kqbk2
            JOIN BaiKiemTra bkt2 ON kqbk2.msbkt = bkt2.msbkt
            WHERE kqbk2.mssv = ?
            AND bkt2.malop = ?
            AND bkt2.danhgia_id = 'A1'
            AND kqbk2.msbkt = bkt.msbkt
        )
        AND chuan.chuan = 'G2.2'
    ORDER BY
        bkt.tenbkt, chuan.chuan
", [$mssv, $malop, $mssv, $malop]);

        // Tính tổng tỷ lệ theo công thức
        $totalPercentage = 0;
        foreach ($result as $row) {
            $ket_qua = $row->ket_qua ?? 0;
            if ($ket_qua != 0) {
                $totalPercentage += (($row->so_cau_dung / $ket_qua) * $row->tile_bai_kiem_tra);
            }
        }

        // Nếu không có kết quả, set totalPercentage = 0
        $totalPercentage = $totalPercentage ?? 0;

        // Lưu kết quả vào bảng KetQuaThanhPhan
        DB::table('KetQuaThanhPhan')->updateOrInsert(
            [
                'mssv' => $mssv,
                'malop' => $malop,
                'thanhphan_id' => 'A1',
                'chuan_id' => 'G2.2',  // Điều kiện nhận diện bản ghi đã tồn tại
            ],
            [
                'tyle' => $totalPercentage,  // Cập nhật tỷ lệ nếu thay đổi
            ]
        );

        // A1 - G3.1
        $result2 = DB::select("
    SELECT
        bkt.msbkt,
        bkt.tenbkt,
        bkt_tile.tile AS tile_bai_kiem_tra,
        tp.thanhphan AS thanhphan_danhgia,
        kqch.chuan_id,
        chuan.chuan AS chuan_dau_ra,
        kqch.so_cau_dung,
        kqbk.diem,
        CASE
            WHEN bkt.loai_bkt = 'TracNghiem' THEN
                (SELECT COUNT(*)
                 FROM CauHoi ch
                 WHERE ch.msbkt = bkt.msbkt AND ch.chuan_id = chuan.id)
            WHEN bkt.loai_bkt = 'TuLuan' THEN
                (SELECT SUM(ch.diem)
                 FROM CauHoi ch
                 WHERE ch.msbkt = bkt.msbkt AND ch.chuan_id = chuan.id)
            ELSE NULL
        END AS ket_qua
    FROM
        BaiKiemTra bkt
    JOIN
        ThanhPhanDanhGia tp ON bkt.danhgia_id = tp.id
    JOIN
        KetQuaBaiKiemTra kqbk ON bkt.msbkt = kqbk.msbkt
    JOIN
        KetQuaChuans kqch ON kqbk.id = kqch.sinhvien_ketqua_id
    JOIN
        ChuanDauRa chuan ON kqch.chuan_id = chuan.id
    JOIN
        BaiKiemTraTile bkt_tile ON bkt.msbkt = bkt_tile.msbkt
    WHERE
        kqbk.mssv = ?  -- Sinh viên (mssv từ URL)
        AND bkt.malop = ?  -- Lớp (malop từ URL)
        AND tp.id = 'A1'
        AND kqbk.diem = (
            SELECT MAX(kqbk2.diem)
            FROM KetQuaBaiKiemTra kqbk2
            JOIN BaiKiemTra bkt2 ON kqbk2.msbkt = bkt2.msbkt
            WHERE kqbk2.mssv = ?
            AND bkt2.malop = ?
            AND bkt2.danhgia_id = 'A1'
            AND kqbk2.msbkt = bkt.msbkt
        )
        AND chuan.chuan = 'G3.1'
    ORDER BY
        bkt.tenbkt, chuan.chuan
", [$mssv, $malop, $mssv, $malop]);

        // Tính tổng tỷ lệ theo công thức
        $totalPercentage2 = 0;
        foreach ($result2 as $row) {
            $ket_qua = $row->ket_qua ?? 0;
            if ($ket_qua != 0) {
                $totalPercentage2 += (($row->so_cau_dung / $ket_qua) * $row->tile_bai_kiem_tra);
            }
        }

        // Nếu không có kết quả, set totalPercentage = 0
        $totalPercentage2 = $totalPercentage2 ?? 0;

        // Lưu kết quả vào bảng KetQuaThanhPhan
        DB::table('KetQuaThanhPhan')->updateOrInsert(
            [
                'mssv' => $mssv,
                'malop' => $malop,
                'thanhphan_id' => 'A1',
                'chuan_id' => 'G3.1',  // Điều kiện nhận diện bản ghi đã tồn tại
            ],
            [
                'tyle' => $totalPercentage2,  // Cập nhật tỷ lệ nếu thay đổi
            ]
        );

        // A3 - G2.2
        $result3 = DB::select("
    SELECT
        bkt.msbkt,
        bkt.tenbkt,
        bkt_tile.tile AS tile_bai_kiem_tra,
        tp.thanhphan AS thanhphan_danhgia,
        kqch.chuan_id,
        chuan.chuan AS chuan_dau_ra,
        kqch.so_cau_dung,
        kqbk.diem,
        CASE
            WHEN bkt.loai_bkt = 'TracNghiem' THEN
                (SELECT COUNT(*)
                 FROM CauHoi ch
                 WHERE ch.msbkt = bkt.msbkt AND ch.chuan_id = chuan.id)
            WHEN bkt.loai_bkt = 'TuLuan' THEN
                (SELECT SUM(ch.diem)
                 FROM CauHoi ch
                 WHERE ch.msbkt = bkt.msbkt AND ch.chuan_id = chuan.id)
            ELSE NULL
        END AS ket_qua
    FROM
        BaiKiemTra bkt
    JOIN
        ThanhPhanDanhGia tp ON bkt.danhgia_id = tp.id
    JOIN
        KetQuaBaiKiemTra kqbk ON bkt.msbkt = kqbk.msbkt
    JOIN
        KetQuaChuans kqch ON kqbk.id = kqch.sinhvien_ketqua_id
    JOIN
        ChuanDauRa chuan ON kqch.chuan_id = chuan.id
    JOIN
        BaiKiemTraTile bkt_tile ON bkt.msbkt = bkt_tile.msbkt
    WHERE
        kqbk.mssv = ?  -- Sinh viên (mssv từ URL)
        AND bkt.malop = ?  -- Lớp (malop từ URL)
        AND tp.id = 'A3'
        AND kqbk.diem = (
            SELECT MAX(kqbk2.diem)
            FROM KetQuaBaiKiemTra kqbk2
            JOIN BaiKiemTra bkt2 ON kqbk2.msbkt = bkt2.msbkt
            WHERE kqbk2.mssv = ?
            AND bkt2.malop = ?
            AND bkt2.danhgia_id = 'A3'
            AND kqbk2.msbkt = bkt.msbkt
        )
        AND chuan.chuan = 'G2.2'
    ORDER BY
        bkt.tenbkt, chuan.chuan
", [$mssv, $malop, $mssv, $malop]);

        // Tính tổng tỷ lệ theo công thức
        $totalPercentage3 = 0;
        foreach ($result3 as $row) {
            $ket_qua = $row->ket_qua ?? 0;
            if ($ket_qua != 0) {
                $totalPercentage3 += (($row->so_cau_dung / $ket_qua) * $row->tile_bai_kiem_tra);
            }
        }

        // Nếu không có kết quả, set totalPercentage = 0
        $totalPercentage3 = $totalPercentage3 ?? 0;

        // Lưu kết quả vào bảng KetQuaThanhPhan
        DB::table('KetQuaThanhPhan')->updateOrInsert(
            [
                'mssv' => $mssv,
                'malop' => $malop,
                'thanhphan_id' => 'A3',
                'chuan_id' => 'G2.2',  // Điều kiện nhận diện bản ghi đã tồn tại
            ],
            [
                'tyle' => $totalPercentage3,  // Cập nhật tỷ lệ nếu thay đổi
            ]
        );

        // A3 - G3.1
        $result4 = DB::select("
    SELECT
        bkt.msbkt,
        bkt.tenbkt,
        bkt_tile.tile AS tile_bai_kiem_tra,
        tp.thanhphan AS thanhphan_danhgia,
        kqch.chuan_id,
        chuan.chuan AS chuan_dau_ra,
        kqch.so_cau_dung,
        kqbk.diem,
        CASE
            WHEN bkt.loai_bkt = 'TracNghiem' THEN
                (SELECT COUNT(*)
                 FROM CauHoi ch
                 WHERE ch.msbkt = bkt.msbkt AND ch.chuan_id = chuan.id)
            WHEN bkt.loai_bkt = 'TuLuan' THEN
                (SELECT SUM(ch.diem)
                 FROM CauHoi ch
                 WHERE ch.msbkt = bkt.msbkt AND ch.chuan_id = chuan.id)
            ELSE NULL
        END AS ket_qua
    FROM
        BaiKiemTra bkt
    JOIN
        ThanhPhanDanhGia tp ON bkt.danhgia_id = tp.id
    JOIN
        KetQuaBaiKiemTra kqbk ON bkt.msbkt = kqbk.msbkt
    JOIN
        KetQuaChuans kqch ON kqbk.id = kqch.sinhvien_ketqua_id
    JOIN
        ChuanDauRa chuan ON kqch.chuan_id = chuan.id
    JOIN
        BaiKiemTraTile bkt_tile ON bkt.msbkt = bkt_tile.msbkt
    WHERE
        kqbk.mssv = ?  -- Sinh viên (mssv từ URL)
        AND bkt.malop = ?  -- Lớp (malop từ URL)
        AND tp.id = 'A3'
        AND kqbk.diem = (
            SELECT MAX(kqbk2.diem)
            FROM KetQuaBaiKiemTra kqbk2
            JOIN BaiKiemTra bkt2 ON kqbk2.msbkt = bkt2.msbkt
            WHERE kqbk2.mssv = ?
            AND bkt2.malop = ?
            AND bkt2.danhgia_id = 'A3'
            AND kqbk2.msbkt = bkt.msbkt
        )
        AND chuan.chuan = 'G3.1'
    ORDER BY
        bkt.tenbkt, chuan.chuan
", [$mssv, $malop, $mssv, $malop]);

        // Tính tổng tỷ lệ theo công thức
        $totalPercentage4 = 0;
        foreach ($result4 as $row) {
            $ket_qua = $row->ket_qua ?? 0;
            if ($ket_qua != 0) {
                $totalPercentage4 += (($row->so_cau_dung / $ket_qua) * $row->tile_bai_kiem_tra);
            }
        }

        // Nếu không có kết quả, set totalPercentage = 0
        $totalPercentage4 = $totalPercentage4 ?? 0;

        // Lưu kết quả vào bảng KetQuaThanhPhan
        DB::table('KetQuaThanhPhan')->updateOrInsert(
            [
                'mssv' => $mssv,
                'malop' => $malop,
                'thanhphan_id' => 'A3',
                'chuan_id' => 'G3.1',  // Điều kiện nhận diện bản ghi đã tồn tại
            ],
            [
                'tyle' => $totalPercentage4,  // Cập nhật tỷ lệ nếu thay đổi
            ]
        );

        // A3 - G3.2
        $result5 = DB::select("
    SELECT
        bkt.msbkt,
        bkt.tenbkt,
        bkt_tile.tile AS tile_bai_kiem_tra,
        tp.thanhphan AS thanhphan_danhgia,
        kqch.chuan_id,
        chuan.chuan AS chuan_dau_ra,
        kqch.so_cau_dung,
        kqbk.diem,
        CASE
            WHEN bkt.loai_bkt = 'TracNghiem' THEN
                (SELECT COUNT(*)
                 FROM CauHoi ch
                 WHERE ch.msbkt = bkt.msbkt AND ch.chuan_id = chuan.id)
            WHEN bkt.loai_bkt = 'TuLuan' THEN
                (SELECT SUM(ch.diem)
                 FROM CauHoi ch
                 WHERE ch.msbkt = bkt.msbkt AND ch.chuan_id = chuan.id)
            ELSE NULL
        END AS ket_qua
    FROM
        BaiKiemTra bkt
    JOIN
        ThanhPhanDanhGia tp ON bkt.danhgia_id = tp.id
    JOIN
        KetQuaBaiKiemTra kqbk ON bkt.msbkt = kqbk.msbkt
    JOIN
        KetQuaChuans kqch ON kqbk.id = kqch.sinhvien_ketqua_id
    JOIN
        ChuanDauRa chuan ON kqch.chuan_id = chuan.id
    JOIN
        BaiKiemTraTile bkt_tile ON bkt.msbkt = bkt_tile.msbkt
    WHERE
        kqbk.mssv = ?  -- Sinh viên (mssv từ URL)
        AND bkt.malop = ?  -- Lớp (malop từ URL)
        AND tp.id = 'A3'
        AND kqbk.diem = (
            SELECT MAX(kqbk2.diem)
            FROM KetQuaBaiKiemTra kqbk2
            JOIN BaiKiemTra bkt2 ON kqbk2.msbkt = bkt2.msbkt
            WHERE kqbk2.mssv = ?
            AND bkt2.malop = ?
            AND bkt2.danhgia_id = 'A3'
            AND kqbk2.msbkt = bkt.msbkt
        )
        AND chuan.chuan = 'G3.2'
    ORDER BY
        bkt.tenbkt, chuan.chuan
", [$mssv, $malop, $mssv, $malop]);

        // Tính tổng tỷ lệ theo công thức
        $totalPercentage5 = 0;
        foreach ($result5 as $row) {
            $ket_qua = $row->ket_qua ?? 0;
            if ($ket_qua != 0) {
                $totalPercentage5 += (($row->so_cau_dung / $ket_qua) * $row->tile_bai_kiem_tra);
            }
        }

        // Nếu không có kết quả, set totalPercentage = 0
        $totalPercentage5 = $totalPercentage5 ?? 0;

        // Lưu kết quả vào bảng KetQuaThanhPhan
        DB::table('KetQuaThanhPhan')->updateOrInsert(
            [
                'mssv' => $mssv,
                'malop' => $malop,
                'thanhphan_id' => 'A3',
                'chuan_id' => 'G3.2',  // Điều kiện nhận diện bản ghi đã tồn tại
            ],
            [
                'tyle' => $totalPercentage5,  // Cập nhật tỷ lệ nếu thay đổi
            ]
        );

        // A4 - G2.2
        $result6 = DB::select("
        SELECT
            bkt.msbkt,
            bkt.tenbkt,
            bkt_tile.tile AS tile_bai_kiem_tra,
            tp.thanhphan AS thanhphan_danhgia,
            kqch.chuan_id,
            chuan.chuan AS chuan_dau_ra,
            kqch.so_cau_dung,
            kqbk.diem,
            CASE
                WHEN bkt.loai_bkt = 'TracNghiem' THEN
                    (SELECT COUNT(*)
                     FROM CauHoi ch
                     WHERE ch.msbkt = bkt.msbkt AND ch.chuan_id = chuan.id)
                WHEN bkt.loai_bkt = 'TuLuan' THEN
                    (SELECT SUM(ch.diem)
                     FROM CauHoi ch
                     WHERE ch.msbkt = bkt.msbkt AND ch.chuan_id = chuan.id)
                ELSE NULL
            END AS ket_qua
        FROM
            BaiKiemTra bkt
        JOIN
            ThanhPhanDanhGia tp ON bkt.danhgia_id = tp.id
        JOIN
            KetQuaBaiKiemTra kqbk ON bkt.msbkt = kqbk.msbkt
        JOIN
            KetQuaChuans kqch ON kqbk.id = kqch.sinhvien_ketqua_id
        JOIN
            ChuanDauRa chuan ON kqch.chuan_id = chuan.id
        JOIN
            BaiKiemTraTile bkt_tile ON bkt.msbkt = bkt_tile.msbkt
        WHERE
            kqbk.mssv = ?  -- Sinh viên (mssv từ URL)
            AND bkt.malop = ?  -- Lớp (malop từ URL)
            AND tp.id = 'A4'
            AND kqbk.diem = (
                SELECT MAX(kqbk2.diem)
                FROM KetQuaBaiKiemTra kqbk2
                JOIN BaiKiemTra bkt2 ON kqbk2.msbkt = bkt2.msbkt
                WHERE kqbk2.mssv = ?
                AND bkt2.malop = ?
                AND bkt2.danhgia_id = 'A4'
                AND kqbk2.msbkt = bkt.msbkt
            )
            AND chuan.chuan = 'G2.2'
        ORDER BY
            bkt.tenbkt, chuan.chuan
    ", [$mssv, $malop, $mssv, $malop]);

        // Tính tổng tỷ lệ theo công thức
        $totalPercentage6 = 0;
        foreach ($result6 as $row) {
            $ket_qua = $row->ket_qua ?? 0;
            if ($ket_qua != 0) {
                $totalPercentage6 += (($row->so_cau_dung / $ket_qua) * $row->tile_bai_kiem_tra);
            }
        }

        // Nếu không có kết quả, set totalPercentage = 0
        $totalPercentage6 = $totalPercentage6 ?? 0;

        // Lưu kết quả vào bảng KetQuaThanhPhan
        DB::table('KetQuaThanhPhan')->updateOrInsert(
            [
                'mssv' => $mssv,
                'malop' => $malop,
                'thanhphan_id' => 'A4',
                'chuan_id' => 'G2.2',  // Điều kiện nhận diện bản ghi đã tồn tại
            ],
            [
                'tyle' => $totalPercentage6,  // Cập nhật tỷ lệ nếu thay đổi
            ]
        );

        // A4 - G3.1
        $result7 = DB::select("
        SELECT
            bkt.msbkt,
            bkt.tenbkt,
            bkt_tile.tile AS tile_bai_kiem_tra,
            tp.thanhphan AS thanhphan_danhgia,
            kqch.chuan_id,
            chuan.chuan AS chuan_dau_ra,
            kqch.so_cau_dung,
            kqbk.diem,
            CASE
                WHEN bkt.loai_bkt = 'TracNghiem' THEN
                    (SELECT COUNT(*)
                     FROM CauHoi ch
                     WHERE ch.msbkt = bkt.msbkt AND ch.chuan_id = chuan.id)
                WHEN bkt.loai_bkt = 'TuLuan' THEN
                    (SELECT SUM(ch.diem)
                     FROM CauHoi ch
                     WHERE ch.msbkt = bkt.msbkt AND ch.chuan_id = chuan.id)
                ELSE NULL
            END AS ket_qua
        FROM
            BaiKiemTra bkt
        JOIN
            ThanhPhanDanhGia tp ON bkt.danhgia_id = tp.id
        JOIN
            KetQuaBaiKiemTra kqbk ON bkt.msbkt = kqbk.msbkt
        JOIN
            KetQuaChuans kqch ON kqbk.id = kqch.sinhvien_ketqua_id
        JOIN
            ChuanDauRa chuan ON kqch.chuan_id = chuan.id
        JOIN
            BaiKiemTraTile bkt_tile ON bkt.msbkt = bkt_tile.msbkt
        WHERE
            kqbk.mssv = ?  -- Sinh viên (mssv từ URL)
            AND bkt.malop = ?  -- Lớp (malop từ URL)
            AND tp.id = 'A4'
            AND kqbk.diem = (
                SELECT MAX(kqbk2.diem)
                FROM KetQuaBaiKiemTra kqbk2
                JOIN BaiKiemTra bkt2 ON kqbk2.msbkt = bkt2.msbkt
                WHERE kqbk2.mssv = ?
                AND bkt2.malop = ?
                AND bkt2.danhgia_id = 'A4'
                AND kqbk2.msbkt = bkt.msbkt
            )
            AND chuan.chuan = 'G3.1'
        ORDER BY
            bkt.tenbkt, chuan.chuan
    ", [$mssv, $malop, $mssv, $malop]);

        // Tính tổng tỷ lệ theo công thức
        $totalPercentage7 = 0;
        foreach ($result7 as $row) {
            $ket_qua = $row->ket_qua ?? 0;
            if ($ket_qua != 0) {
                $totalPercentage7 += (($row->so_cau_dung / $ket_qua) * $row->tile_bai_kiem_tra);
            }
        }

        // Nếu không có kết quả, set totalPercentage = 0
        $totalPercentage7 = $totalPercentage7 ?? 0;

        // Lưu kết quả vào bảng KetQuaThanhPhan
        DB::table('KetQuaThanhPhan')->updateOrInsert(
            [
                'mssv' => $mssv,
                'malop' => $malop,
                'thanhphan_id' => 'A4',
                'chuan_id' => 'G3.1',  // Điều kiện nhận diện bản ghi đã tồn tại
            ],
            [
                'tyle' => $totalPercentage7,  // Cập nhật tỷ lệ nếu thay đổi
            ]
        );

        // A4 - G3.2
        $result8 = DB::select("
        SELECT
            bkt.msbkt,
            bkt.tenbkt,
            bkt_tile.tile AS tile_bai_kiem_tra,
            tp.thanhphan AS thanhphan_danhgia,
            kqch.chuan_id,
            chuan.chuan AS chuan_dau_ra,
            kqch.so_cau_dung,
            kqbk.diem,
            CASE
                WHEN bkt.loai_bkt = 'TracNghiem' THEN
                    (SELECT COUNT(*)
                     FROM CauHoi ch
                     WHERE ch.msbkt = bkt.msbkt AND ch.chuan_id = chuan.id)
                WHEN bkt.loai_bkt = 'TuLuan' THEN
                    (SELECT SUM(ch.diem)
                     FROM CauHoi ch
                     WHERE ch.msbkt = bkt.msbkt AND ch.chuan_id = chuan.id)
                ELSE NULL
            END AS ket_qua
        FROM
            BaiKiemTra bkt
        JOIN
            ThanhPhanDanhGia tp ON bkt.danhgia_id = tp.id
        JOIN
            KetQuaBaiKiemTra kqbk ON bkt.msbkt = kqbk.msbkt
        JOIN
            KetQuaChuans kqch ON kqbk.id = kqch.sinhvien_ketqua_id
        JOIN
            ChuanDauRa chuan ON kqch.chuan_id = chuan.id
        JOIN
            BaiKiemTraTile bkt_tile ON bkt.msbkt = bkt_tile.msbkt
        WHERE
            kqbk.mssv = ?  -- Sinh viên (mssv từ URL)
            AND bkt.malop = ?  -- Lớp (malop từ URL)
            AND tp.id = 'A4'
            AND kqbk.diem = (
                SELECT MAX(kqbk2.diem)
                FROM KetQuaBaiKiemTra kqbk2
                JOIN BaiKiemTra bkt2 ON kqbk2.msbkt = bkt2.msbkt
                WHERE kqbk2.mssv = ?
                AND bkt2.malop = ?
                AND bkt2.danhgia_id = 'A4'
                AND kqbk2.msbkt = bkt.msbkt
            )
            AND chuan.chuan = 'G3.2'
        ORDER BY
            bkt.tenbkt, chuan.chuan
    ", [$mssv, $malop, $mssv, $malop]);

        // Tính tổng tỷ lệ theo công thức
        $totalPercentage8 = 0;
        foreach ($result8 as $row) {
            $ket_qua = $row->ket_qua ?? 0;
            if ($ket_qua != 0) {
                $totalPercentage8 += (($row->so_cau_dung / $ket_qua) * $row->tile_bai_kiem_tra);
            }
        }

        // Nếu không có kết quả, set totalPercentage = 0
        $totalPercentage8 = $totalPercentage8 ?? 0;

        // Lưu kết quả vào bảng KetQuaThanhPhan
        DB::table('KetQuaThanhPhan')->updateOrInsert(
            [
                'mssv' => $mssv,
                'malop' => $malop,
                'thanhphan_id' => 'A4',
                'chuan_id' => 'G3.2',  // Điều kiện nhận diện bản ghi đã tồn tại
            ],
            [
                'tyle' => $totalPercentage8,  // Cập nhật tỷ lệ nếu thay đổi
            ]
        );

        // A4 - G6.1
        $result9 = DB::select("
        SELECT
            bkt.msbkt,
            bkt.tenbkt,
            bkt_tile.tile AS tile_bai_kiem_tra,
            tp.thanhphan AS thanhphan_danhgia,
            kqch.chuan_id,
            chuan.chuan AS chuan_dau_ra,
            kqch.so_cau_dung,
            kqbk.diem,
            CASE
                WHEN bkt.loai_bkt = 'TracNghiem' THEN
                    (SELECT COUNT(*)
                     FROM CauHoi ch
                     WHERE ch.msbkt = bkt.msbkt AND ch.chuan_id = chuan.id)
                WHEN bkt.loai_bkt = 'TuLuan' THEN
                    (SELECT SUM(ch.diem)
                     FROM CauHoi ch
                     WHERE ch.msbkt = bkt.msbkt AND ch.chuan_id = chuan.id)
                ELSE NULL
            END AS ket_qua
        FROM
            BaiKiemTra bkt
        JOIN
            ThanhPhanDanhGia tp ON bkt.danhgia_id = tp.id
        JOIN
            KetQuaBaiKiemTra kqbk ON bkt.msbkt = kqbk.msbkt
        JOIN
            KetQuaChuans kqch ON kqbk.id = kqch.sinhvien_ketqua_id
        JOIN
            ChuanDauRa chuan ON kqch.chuan_id = chuan.id
        JOIN
            BaiKiemTraTile bkt_tile ON bkt.msbkt = bkt_tile.msbkt
        WHERE
            kqbk.mssv = ?  -- Sinh viên (mssv từ URL)
            AND bkt.malop = ?  -- Lớp (malop từ URL)
            AND tp.id = 'A4'
            AND kqbk.diem = (
                SELECT MAX(kqbk2.diem)
                FROM KetQuaBaiKiemTra kqbk2
                JOIN BaiKiemTra bkt2 ON kqbk2.msbkt = bkt2.msbkt
                WHERE kqbk2.mssv = ?
                AND bkt2.malop = ?
                AND bkt2.danhgia_id = 'A4'
                AND kqbk2.msbkt = bkt.msbkt
            )
            AND chuan.chuan = 'G6.1'
        ORDER BY
            bkt.tenbkt, chuan.chuan
    ", [$mssv, $malop, $mssv, $malop]);

        // Tính tổng tỷ lệ theo công thức
        $totalPercentage9 = 0;
        foreach ($result9 as $row) {
            $ket_qua = $row->ket_qua ?? 0;
            if ($ket_qua != 0) {
                $totalPercentage9 += (($row->so_cau_dung / $ket_qua) * $row->tile_bai_kiem_tra);
            }
        }

        // Nếu không có kết quả, set totalPercentage = 0
        $totalPercentage9 = $totalPercentage9 ?? 0;

        // Lưu kết quả vào bảng KetQuaThanhPhan
        DB::table('KetQuaThanhPhan')->updateOrInsert(
            [
                'mssv' => $mssv,
                'malop' => $malop,
                'thanhphan_id' => 'A4',
                'chuan_id' => 'G6.1',  // Điều kiện nhận diện bản ghi đã tồn tại
            ],
            [
                'tyle' => $totalPercentage9,  // Cập nhật tỷ lệ nếu thay đổi
            ]
        );

        return view('student.view.classes', compact('class', 'totalPercentage', 'totalPercentage2', 'totalPercentage3', 'totalPercentage4', 'totalPercentage5', 'totalPercentage6', 'totalPercentage7', 'totalPercentage8', 'totalPercentage9'));
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

        // Tính điểm trung bình
        $averageScore = $scores->filter(fn($score) => is_numeric($score['diem']))
            ->avg(fn($score) => $score['diem']);

        return view('student.view.scores', compact('class', 'scores', 'averageScore'));
    }

    public function show($malop, $id)
    {
        // Tìm bài giảng theo ID
        $lecture = BaiGiang::findOrFail($id);
        $user = Session::get('user');
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

        // Trả về view chi tiết bài giảng
        return view('student.detail.lecture', compact('lecture', 'class'));
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
        $user = Session::get('user');
        $mssv = $user['id'];
        if (!$test) {
            return redirect()->route('student.classlist')->withErrors(['error' => 'Bài kiểm tra không tồn tại']);
        }

        // Lấy thông tin về các lần làm bài của sinh viên đối với bài kiểm tra
        $testResults = DB::table('LichSuLamBaiKiemTra')
            ->join('KetQuaBaiKiemTra', 'LichSuLamBaiKiemTra.id', '=', 'KetQuaBaiKiemTra.lich_su_id')
            ->join('SinhVien', 'LichSuLamBaiKiemTra.mssv', '=', 'SinhVien.mssv')
            ->where('LichSuLamBaiKiemTra.msbkt', $test->msbkt)
            ->where('LichSuLamBaiKiemTra.malop', $malop)
            ->where('LichSuLamBaiKiemTra.mssv', $mssv)
            ->select('LichSuLamBaiKiemTra.solanlam', 'KetQuaBaiKiemTra.diem', 'KetQuaBaiKiemTra.cau_tra_loi', 'SinhVien.tensv', 'SinhVien.mssv')
            ->get();

        // Trả về view với dữ liệu
        return view('student.detail.test', [
            'malop' => $malop,
            'msbkt' => $msbkt,
            'test' => $test, // Truyền bài kiểm tra duy nhất vào view
            'testResults' => $testResults, // Truyền thông tin kết quả làm bài của sinh viên
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
        $test = BaiKiemTra::where('msbkt', $msbkt)->with('cauHoi')->first();
        $user = Session::get('user');
        $mssv = $user['id'];

        if (!$test) {
            return redirect()->route('student.classlist')->withErrors(['error' => 'Bài kiểm tra không tồn tại']);
        }

        // Lấy kết quả bài kiểm tra của sinh viên
        $ketQuaBaiKiemTra = KetQuaBaiKiemTra::where('msbkt', $msbkt)
            ->where('mssv', $mssv)
            ->first();

        if (!$ketQuaBaiKiemTra) {
            return redirect()->route('student.classlist')->withErrors(['error' => 'Bạn chưa làm bài kiểm tra này']);
        }

        // Lấy nhận xét của giáo viên
        $nhanXet = NhanXetBaiKiemTra::where('ketqua_id', $ketQuaBaiKiemTra->id)->first();

        // Lấy đường dẫn file (nếu có)
        $filesPath = $ketQuaBaiKiemTra->files_path;
        $filesArray = $filesPath ? explode(',', $filesPath) : [];

        return view('student.detail.essay', [
            'test' => $test,
            'malop' => $malop,
            'filesArray' => $filesArray,
            'diem' => $ketQuaBaiKiemTra->diem,  // Trả điểm cho bài kiểm tra
            'nhanXet' => $nhanXet ? $nhanXet->nhanxet : 'Chưa có nhận xét' // Nếu không có nhận xét, trả về thông báo
        ]);
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