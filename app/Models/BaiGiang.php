<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaiGiang extends Model
{
    protected $table = 'BaiGiang';
    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'int';
    protected $primaryKey = 'msbg';


    protected $fillable = [
        'tenbg',
        'file_paths',
        'link_paths',
        'noidungbg',
        'malop'
    ];

    // Quan hệ với bảng LopHoc qua malop
    public function lopHoc()
    {
        return $this->belongsTo(LopHoc::class, 'malop', 'malop');
    }
}
