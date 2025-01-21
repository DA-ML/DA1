<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThongBao extends Model
{
    use HasFactory;
    public $timestamps = true;
    public $incrementing = true;
    protected $keyType = 'int';
    protected $table = 'ThongBao';
    protected $fillable = ['mssv', 'msbkt', 'message', 'is_read', 'created_at', 'updated_at'];

    public function baiKiemTra()
    {
        return $this->belongsTo(BaiKiemTra::class, 'msbkt', 'msbkt');
    }
}
