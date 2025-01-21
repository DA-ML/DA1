<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThongBao extends Model
{
    use HasFactory;

    protected $table = 'ThongBao';
    protected $fillable = ['mssv', 'msbkt', 'message', 'is_read'];

    public function baiKiemTra()
    {
        return $this->belongsTo(BaiKiemTra::class, 'msbkt', 'msbkt');
    }
}
