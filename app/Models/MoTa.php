<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoTa extends Model
{
    use HasFactory;

    protected $table = 'MoTa';
    protected $fillable = ['malop', 'mota'];

    public function lopHoc()
    {
        return $this->belongsTo(LopHoc::class, 'malop', 'malop');
    }
}
