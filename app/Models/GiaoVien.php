<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GiaoVien extends Model
{
    protected $table = 'GiaoVien';
    protected $primaryKey = 'msgv';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = ['msgv', 'password_gv', 'tengv', 'ngaysinh', 'emailgv', 'khoa'];
    public $timestamps = false;
}
