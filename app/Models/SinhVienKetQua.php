<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SinhVienKetQua extends Model
{
    use HasFactory;

    protected $table = 'SinhVienKetQua';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'mssv',
        'msbkt',
        'malop',
    ];

    // Định nghĩa quan hệ với bảng `KetQuaChuans`
    public function ketQuaChuans()
    {
        return $this->hasMany(KetQuaChuans::class, 'sinhvien_ketqua_id');
    }

    // Định nghĩa quan hệ với bảng `BaiKiemTra`
    public function baiKiemTra()
    {
        return $this->belongsTo(BaiKiemTra::class, 'msbkt');
    }

    // Định nghĩa quan hệ với bảng `LopHoc`
    public function lopHoc()
    {
        return $this->belongsTo(LopHoc::class, 'malop');
    }
}
