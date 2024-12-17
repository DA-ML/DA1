<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThanhPhanDanhGia extends Model
{
    // Tên bảng
    protected $table = 'ThanhPhanDanhGia';
    protected $primaryKey = 'id';
    public $incrementing = false;
    // Định nghĩa kiểu dữ liệu của khóa chính
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'thanhphan',
        'tile',
    ];
}
