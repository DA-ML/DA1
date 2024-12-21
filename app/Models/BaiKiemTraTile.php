<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaiKiemTraTile extends Model
{
    use HasFactory;

    // Đặt tên bảng, nếu tên bảng không phải là dạng mặc định (plural của tên model)
    protected $table = 'BaiKiemTraTile';

    // Để khai báo khóa chính, vì khóa chính của bạn là một cặp (msbkt, malop)
    protected $primaryKey = ['msbkt', 'malop'];

    // Vì khóa chính là composite, bạn cần cấu hình $incrementing và $keyType
    public $incrementing = false;
    protected $keyType = 'string';

    // Các trường có thể gán giá trị đại diện cho dữ liệu cần lưu trong cơ sở dữ liệu
    protected $fillable = [
        'msbkt',
        'malop',
        'tile',
    ];

    // Các trường không được gán giá trị đại diện cho các cột không cần thêm giá trị từ người dùng
    protected $guarded = [];

    // Cấu hình quan hệ giữa các bảng
    public function baiKiemTra()
    {
        return $this->belongsTo(BaiKiemTra::class, 'msbkt', 'msbkt');
    }

    public function lopHoc()
    {
        return $this->belongsTo(LopHoc::class, 'malop', 'malop');
    }

    // Thêm một phương thức để kiểm tra tổng tỷ lệ
    // public static function checkTotalPercentage($malop)
    // {
    //     // Tính tổng tỷ lệ của các bài kiểm tra trong lớp học
    //     $total = self::where('malop', $malop)->sum('tile');

    //     return $total;
    // }
}
