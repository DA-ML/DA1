<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoTa extends Model
{
    use HasFactory;

    public $timestamps = true;
    public $incrementing = true;
    protected $keyType = 'int';
    protected $table = 'MoTa';
    protected $fillable = ['malop', 'mota', 'image_path', 'created_at', 'updated_at'];

    public function lopHoc()
    {
        return $this->belongsTo(LopHoc::class, 'malop', 'malop');
    }
}
