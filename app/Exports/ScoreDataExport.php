<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ScoreDataExport implements FromArray, WithHeadings
{
    protected $studentsWithResults;

    public function __construct($studentsWithResults)
    {
        $this->studentsWithResults = $studentsWithResults;
    }

    // Tạo mảng dữ liệu từ Controller
    public function array(): array
    {
        $data = [];

        foreach ($this->studentsWithResults as $studentData) {
            $studentName = $studentData['ten_sv'];  // Sử dụng 'ten_sv' thay vì sinh_vien->tensv
            $studentId = $studentData['mssv'];     // Sử dụng 'mssv' thay vì sinh_vien->mssv

            // Tạo mảng điểm của sinh viên
            $examScores = [];
            $totalScore = 0;
            $numExams = 0;
            foreach ($studentData['ket_qua'] as $result) {
                $score = $result['diem'] !== null ? $result['diem'] : 0; // Nếu không có điểm thì tính là 0
                $examScores[] = $score;

                if ($score !== 0) {
                    $totalScore += $score; // Tính tổng điểm
                    $numExams++; // Đếm số bài kiểm tra có điểm
                }
            }

            // Thêm thông tin sinh viên và điểm vào mảng
            $averageScore = $numExams > 0 ? $totalScore / $numExams : '-';

            // Thêm thông tin sinh viên và điểm vào mảng
            $data[] = array_merge([$studentName, $studentId], $examScores, [$averageScore]);
        }

        return $data;
    }

    public function headings(): array
    {
        $heading = ['Tên Sinh Viên', 'MSSV'];

        // Lấy tên các bài kiểm tra từ dữ liệu (bài kiểm tra có thể thay đổi theo lớp)
        foreach ($this->studentsWithResults[0]['ket_qua'] as $result) {
            $heading[] = $result['bai_kiem_tra']; // Tên bài kiểm tra
        }

        // Thêm cột điểm trung bình
        $heading[] = 'Điểm Trung Bình';

        return $heading;
    }
}
