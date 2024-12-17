<?php

namespace App\Http\Controllers;

use App\Models\QuanLyTPDG;
use Illuminate\Http\Request;

class QuanLyTPDGController extends Controller
{
    public function getCDR(Request $request)
    {
        $thanhphanId = $request->query('tpdg');

        if (!$thanhphanId) {
            return response()->json(['error' => 'Thành phần đánh giá không được để trống'], 400);
        }

        // Lấy danh sách chuẩn đầu ra (CDR) liên kết với TPDG
        $cdrs = QuanLyTPDG::where('thanhphan_id', $thanhphanId)
            ->with('chuanDauRa:id,chuan') // Lấy dữ liệu từ bảng ChuanDauRa
            ->get()
            ->map(function ($item) {
                return $item->chuanDauRa;  // Trả về toàn bộ đối tượng chuanDauRa
            });

        if ($cdrs->isEmpty()) {
            return response()->json(['message' => 'Không tìm thấy chuẩn đầu ra cho thành phần đánh giá này.'], 404);
        }

        return response()->json([
            'thanhphanId' => $thanhphanId,
            'cdrs' => $cdrs // Kiểm tra dữ liệu đã lấy được
        ]);
    }
}
