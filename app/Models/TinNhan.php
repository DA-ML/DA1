<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TinNhan extends Model
{
    protected $table = 'TinNhan';
    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'nguoigui',
        'nguoinhan',
        'noidung',
        'thoigiangui'
    ];

    // Quan hệ với bảng `SinhVien` qua `nguoigui` (người gửi)
    public function nguoiGui()
    {
        return $this->belongsTo(SinhVien::class, 'nguoigui', 'mssv');
    }

    // Quan hệ với bảng `SinhVien` qua `nguoinhan` (người nhận)
    public function nguoiNhan()
    {
        return $this->belongsTo(SinhVien::class, 'nguoinhan', 'mssv');
    }
}
