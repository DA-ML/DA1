<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaiKiemTra extends Model
{
    protected $table = 'BaiKiemTra';
    public $timestamps = false;
    protected $primaryKey = 'msbkt';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'tenbkt',
        'ngaybatdau',
        'ngayketthuc',
        'danhgia_id',
        'loai_bkt',
        'num_ques',
        'thoigianlambai',
        'solanlam',
        'file_path',
        'malop',
    ];

    // Quan hệ với bảng ThanhPhanDanhGia qua danhgia_id
    public function thanhPhanDanhGia()
    {
        return $this->belongsTo(ThanhPhanDanhGia::class, 'danhgia_id', 'id');
    }

    // Quan hệ với bảng LopHoc qua malop
    public function lopHoc()
    {
        return $this->belongsTo(LopHoc::class, 'malop', 'malop');
    }

    public function cauHoi()
    {
        return $this->hasMany(CauHoi::class, 'msbkt', 'msbkt');
    }

    // Quan hệ 1 - Nhiều: Một bài kiểm tra có nhiều câu trả lời
    public function cauTraLoi()
    {
        return $this->hasMany(CauTraLoi::class, 'msbkt', 'msbkt');
    }
}
