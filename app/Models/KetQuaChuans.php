<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KetQuaChuans extends Model
{
    use HasFactory;

    protected $table = 'KetQuaChuans';
    protected $fillable = [
        'sinhvien_ketqua_id',
        'chuan_id',
        'so_cau_dung',
    ];

    public $incrementing = true;
    protected $keyType = 'int';

    // Quan hệ với bảng `SinhVienKetQua`
    public function sinhVienKetQua()
    {
        return $this->belongsTo(SinhVienKetQua::class, 'sinhvien_ketqua_id');
    }

    // Quan hệ với bảng `ChuanDauRa`
    public function chuanDauRa()
    {
        return $this->belongsTo(ChuanDauRa::class, 'chuan_id');
    }
}
