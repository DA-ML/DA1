<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KetQuaThanhPhan extends Model
{
    use HasFactory;
    protected $table = 'ketquathanhphan';
    protected $primaryKey = 'id';
    protected $fillable = [
        'mssv',
        'thanhphan_id',
        'chuan_id',
        'tile_dat_duoc',
        'malop',
        'updated_at',
        'created_at',
    ];

    public $timestamps = true;

    // Quan hệ với bảng `SinhVien`
    public function sinhVien()
    {
        return $this->belongsTo(SinhVien::class, 'mssv', 'mssv');
    }

    // Quan hệ với bảng `ThanhPhanDanhGia`
    public function thanhPhanDanhGia()
    {
        return $this->belongsTo(ThanhPhanDanhGia::class, 'thanhphan_id', 'id');
    }

    /**
     * Quan hệ với bảng `ChuanDauRa`.
     */
    public function chuanDauRa()
    {
        return $this->belongsTo(ChuanDauRa::class, 'chuan_id', 'id');
    }

    /**
     * Quan hệ với bảng `LopHoc`.
     */
    public function lopHoc()
    {
        return $this->belongsTo(LopHoc::class, 'malop', 'malop');
    }
}
