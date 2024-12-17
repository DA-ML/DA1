<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GiaoVien extends Model
{
    protected $table = 'GiaoVien';
    protected $primaryKey = 'msgv';
    protected $keyType = 'string'; // Đảm bảo rằng kiểu của khóa chính là chuỗi (string)
    public $incrementing = false; // Tắt tính năng tự tăng của khóa chính
    protected $fillable = ['msgv', 'password_gv', 'tengv', 'emailgv', 'khoa'];
    public $timestamps = false;
}
