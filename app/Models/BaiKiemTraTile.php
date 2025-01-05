<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaiKiemTraTile extends Model
{
    use HasFactory;

    protected $table = 'BaiKiemTraTile';
    public $timestamps = false;
    protected $primaryKey = ['msbkt', 'malop'];

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'msbkt',
        'malop',
        'tile',
    ];

    protected $guarded = [];

    // Cấu hình quan hệ giữa các bảng
    public function baiKiemTra()
    {
        return $this->belongsTo(BaiKiemTra::class, 'msbkt', 'msbkt');
    }

    public function lopHoc()
    {
        return $this->belongsTo(LopHoc::class, 'malop', 'malop');
    }
}
