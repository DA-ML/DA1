<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NhanXetBaiKiemTra extends Model
{
    protected $table = 'NhanXetBaiKiemTra';
    public $timestamps = false;
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'ketqua_id',
        'msgv',
        'nhanxet',
        'thoigian',
    ];

    // Quan hệ với bảng KetQuaBaiKiemTra
    public function ketQuaBaiKiemTra()
    {
        return $this->belongsTo(KetQuaBaiKiemTra::class, 'ketqua_id', 'id');
    }

    // Quan hệ với bảng GiaoVien
    public function giaoVien()
    {
        return $this->belongsTo(GiaoVien::class, 'msgv', 'msgv');
    }
}
