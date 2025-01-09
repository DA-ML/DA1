<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LichSuLamBaiKiemTra extends Model
{
    use HasFactory;

    protected $table = 'LichSuLamBaiKiemTra'; // Định nghĩa bảng
    public $timestamps = false; // Bảng này không sử dụng timestamps (created_at, updated_at)
    protected $primaryKey = 'id'; // Khóa chính
    public $incrementing = true;
    protected $keyType = 'int'; // Định nghĩa kiểu dữ liệu của khóa chính

    // Các thuộc tính có thể được gán (fillable)
    protected $fillable = [
        'msbkt',
        'mssv',
        'malop',
        'solanlam',
    ];

    // Quan hệ với bảng BaiKiemTra
    public function baiKiemTra()
    {
        return $this->belongsTo(BaiKiemTra::class, 'msbkt', 'msbkt');
    }

    // Quan hệ với bảng SinhVien
    public function sinhVien()
    {
        return $this->belongsTo(SinhVien::class, 'mssv', 'mssv');
    }

    // Quan hệ với bảng LopHoc
    public function lopHoc()
    {
        return $this->belongsTo(LopHoc::class, 'malop', 'malop');
    }

    // Quan hệ với bảng KetQuaBaiKiemTra
    public function ketQuaBaiKiemTra()
    {
        return $this->hasOne(KetQuaBaiKiemTra::class, 'lich_su_id', 'id');
    }
}
