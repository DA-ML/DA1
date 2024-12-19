<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuanLyHS extends Model
{
    use HasFactory;

    protected $table = 'QuanLyHS';

    protected $fillable = [
        'mssv',
        'malop',
    ];

    public function sinhVien()
    {
        return $this->belongsTo(SinhVien::class, 'mssv', 'mssv'); // Liên kết với bảng sinhvien
    }
}
