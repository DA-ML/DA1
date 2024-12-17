<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CauHoi extends Model
{
    // Tên bảng
    protected $table = 'CauHoi';
    protected $primaryKey = 'msch';
    public $incrementing = true;
    // Định nghĩa kiểu dữ liệu của khóa chính
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'chuan_id',
        'dapan',
        'diem',
        'msbkt'
    ];

    // Định nghĩa mối quan hệ với bảng `ChuanDauRa`
    public function chuanDauRa()
    {
        return $this->belongsTo(ChuanDauRa::class, 'chuan_id', 'id');
    }

    // Định nghĩa mối quan hệ với bảng `BaiKiemTra`
    public function baiKiemTra()
    {
        return $this->belongsTo(BaiKiemTra::class, 'msbkt', 'msbkt');
    }
}
