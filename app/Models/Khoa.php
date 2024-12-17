<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Khoa extends Model
{
    // Tên bảng
    protected $table = 'Khoa';
    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'makhoa',
        'tenkhoa',
    ];
}
