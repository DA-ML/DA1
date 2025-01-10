<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LopHoc extends Model
{
    // Tên bảng
    protected $table = 'LopHoc';
    protected $primaryKey = 'malop';
    public $incrementing = false;
    // Định nghĩa kiểu dữ liệu của khóa chính
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'malop',
        'tenlop',
    ];

    public function quanLyHS()
    {
        return $this->hasMany(QuanLyHS::class, 'malop', 'malop');
    }

    // Quan hệ với bảng QuanLyGV (Quản lý giáo viên)
    public function quanLyGV()
    {
        return $this->hasMany(QuanLyGV::class, 'malop', 'malop');
    }

    public function baiGiang()
    {
        return $this->hasMany(BaiGiang::class, 'malop', 'malop');
    }

    public function baiKiemTra()
    {
        return $this->hasMany(BaiKiemTra::class, 'malop', 'malop');
    }

    public function hocKy()
    {
        return $this->belongsTo(HocKy::class, 'mahk', 'mahk');
    }
}
