<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuanLyKhoa extends Model
{
    // Tên bảng
    protected $table = 'QuanLyKhoa';
    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'makhoa',
        'malop',
    ];

    // Mối quan hệ với bảng Khoa
    public function khoa()
    {
        return $this->belongsTo(Khoa::class, 'makhoa', 'makhoa');
    }

    // Mối quan hệ với bảng LopHoc
    public function lopHoc()
    {
        return $this->belongsTo(LopHoc::class, 'malop', 'malop');
    }
}
