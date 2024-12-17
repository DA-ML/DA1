<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChuanDauRa extends Model
{
    // Tên bảng
    protected $table = 'ChuanDauRa';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $timestamps = false;

    // Định nghĩa kiểu dữ liệu của khóa chính
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'chuan',
    ];
}
