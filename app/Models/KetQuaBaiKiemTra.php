<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KetQuaBaiKiemTra extends Model
{
    protected $table = 'KetQuaBaiKiemTra';
    public $timestamps = false; // Bảng này không sử dụng timestamps (created_at, updated_at)
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    // Các thuộc tính có thể được gán (fillable)
    protected $fillable = [
        'msbkt',
        'mssv',
        'diem',
        'cau_tra_loi',
        'files_path',
        'lich_su_id',
    ];

    protected $casts = [
        'cau_tra_loi' => 'array',
    ];

    // Quan hệ với bảng BaiKiemTra
    public function baiKiemTra()
    {
        return $this->belongsTo(BaiKiemTra::class, 'msbkt', 'msbkt');
    }

    // Quan hệ với bảng SinhVien
    public function sinhVien()
    {
        return $this->belongsTo(SinhVien::class, 'mssv', 'mssv');
    }

    // Quan hệ với bảng NhanXetBaiKiemTra
    public function nhanXetBaiKiemTra()
    {
        return $this->hasMany(NhanXetBaiKiemTra::class, 'ketqua_id', 'id');
    }

    public function lichSuLamBaiKiemTra()
    {
        return $this->belongsTo(LichSuLamBaiKiemTra::class, 'lich_su_id', 'id');
    }
}
