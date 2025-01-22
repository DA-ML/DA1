<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ScoreDataExport implements FromArray, WithHeadings
{
    protected $studentsWithResults;
    protected $baiKiemTras;

    public function __construct($studentsWithResults, $baiKiemTras)
    {
        $this->studentsWithResults = $studentsWithResults;
        $this->baiKiemTras = $baiKiemTras;
    }

    public function array(): array
    {
        $data = [];

        foreach ($this->studentsWithResults as $studentData) {
            $studentName = $studentData['ten_sv'];
            $studentId = $studentData['mssv'];

            $examScores = [];
            $weightedTotal = 0;
            $totalWeight = 0;

            foreach ($studentData['ket_qua'] as $result) {
                $score = $result['diem'] !== '-' ? floatval($result['diem']) : '-';
                $tile = $result['tile'] !== null ? floatval($result['tile']) : 0;

                $examScores[] = $score;

                // Tính tổng trọng số và điểm trung bình có trọng số
                if ($score !== '-') {
                    $weightedTotal += $score * $tile;
                    $totalWeight += $tile;
                }
            }

            // Tính điểm trung bình
            $averageScore = $totalWeight > 0 ? round($weightedTotal / $totalWeight, 2) : '-';

            $data[] = array_merge([$studentName, $studentId], $examScores, [$averageScore]);
        }

        return $data;
    }

    public function headings(): array
    {
        $heading = ['Tên Sinh Viên', 'MSSV'];

        foreach ($this->baiKiemTras as $baiKiemTra) {
            $tile = $baiKiemTra->tile !== null ? " ({$baiKiemTra->tile}%)" : '';
            $heading[] = "{$baiKiemTra->tenbkt}{$tile}";
        }

        $heading[] = 'Điểm Trung Bình';

        return $heading;
    }
}
