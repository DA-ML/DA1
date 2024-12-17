<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuanLyHocKy extends Model
{
    protected $table = 'QuanLyHocKy';
    public $timestamps = false;
    protected $fillable = [
        'mahk',
        'makhoa',
    ];

    public $incrementing = false;
    protected $keyType = 'string';

    // Quan hệ với bảng HocKy
    public function hocKy()
    {
        return $this->belongsTo(HocKy::class, 'mahk', 'mahk');
    }

    // Quan hệ với bảng Khoa
    public function khoa()
    {
        return $this->belongsTo(Khoa::class, 'makhoa', 'makhoa');
    }
}
