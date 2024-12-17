<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SinhVien extends Model
{
    use HasFactory;

    protected $table = 'SinhVien';
    protected $primaryKey = 'mssv';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'mssv',
        'password_sv',
        'tensv',
        'ngaysinh',
        'emailsv',
        'hedaotao',
    ];
}
