<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuanLyTPDG extends Model
{
    protected $table = 'QuanLyTPDG';
    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'thanhphan_id',
        'chuan_id',
    ];

    // Quan hệ với bảng ThanhPhanDanhGia
    public function thanhPhanDanhGia()
    {
        return $this->belongsTo(ThanhPhanDanhGia::class, 'thanhphan_id', 'id');
    }

    // Quan hệ với bảng ChuanDauRa
    public function chuanDauRa()
    {
        return $this->belongsTo(ChuanDauRa::class, 'chuan_id', 'id');
    }
}
