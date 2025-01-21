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

    // Quan hệ với bảng ThanhPhanDanhGia
    public function thanhPhanDanhGia()
    {
        return $this->belongsTo(ThanhPhanDanhGia::class, 'danhgia_id', 'id');
    }

    // Quan hệ với bảng LopHoc
    public function lopHoc()
    {
        return $this->belongsTo(LopHoc::class, 'malop', 'malop');
    }

    // Quan hệ với bảng CauHoi
    public function cauHoi()
    {
        return $this->hasMany(CauHoi::class, 'msbkt', 'msbkt');
    }

    // Quan hệ với bảng CauTraLoi
    public function cauTraLoi()
    {
        return $this->hasMany(CauTraLoi::class, 'msbkt', 'msbkt');
    }

    // Quan hệ với bảng KetQuaBaiKiemTra
    public function ketQuaBaiKiemTra()
    {
        return $this->hasMany(KetQuaBaiKiemTra::class, 'msbkt', 'msbkt');
    }

    public function thongBaos()
    {
        return $this->hasMany(ThongBao::class, 'msbkt', 'msbkt');
    }
}
