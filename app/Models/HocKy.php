<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HocKy extends Model
{
    protected $table = 'HocKy';
    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'mahk',
        'tenhk',
        'namhoc',
    ];

    public function lopHocs()
    {
        return $this->hasMany(LopHoc::class, 'mahk', 'mahk');
    }
}
