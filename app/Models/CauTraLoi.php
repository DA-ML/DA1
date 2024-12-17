<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CauTraLoi extends Model
{
    use HasFactory;

    protected $table = 'cautraloi';
    protected $fillable = ['cauhoi_id', 'sinhvien_id', 'dap_an_chon', 'dung_hay_sai'];

    public function cauHoi()
    {
        return $this->belongsTo(CauHoi::class, 'cauhoi_id', 'msch');
    }
}
