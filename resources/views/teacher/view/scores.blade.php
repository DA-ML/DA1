<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Teacher Score</title>
</head>

<Div class="teacher-viewclass">
    @include('components.heading')
    <div class="body">
        @include('components.sidebar')
        <div class="right">
            <div class="class-list">
                <div class="class-name">
                    Lớp học: {{ $class->tenlop }}
                    <div class="class-id">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none">
                            <path
                                d="M3 13H21V11H3V13ZM12 20C7.58172 20 4 16.4183 4 12H2C2 17.5228 6.47715 22 12 22V20ZM4 12C4 7.58172 7.58172 4 12 4V2C6.47715 2 2 6.47715 2 12H4ZM12 4C16.4183 4 20 7.58172 20 12H22C22 6.47715 17.5228 2 12 2V4ZM20 12C20 16.4183 16.4183 20 12 20V22C17.5228 22 22 17.5228 22 12H20ZM12 20C11.7731 20 11.4816 19.9007 11.1324 19.5683C10.778 19.2311 10.4134 18.6958 10.0854 17.9578C9.43054 16.4844 9 14.3789 9 12H7C7 14.5917 7.46489 16.9861 8.25776 18.7701C8.65364 19.6608 9.15092 20.4435 9.75363 21.0171C10.3615 21.5956 11.1223 22 12 22V20ZM9 12C9 9.62114 9.43054 7.51558 10.0854 6.04218C10.4134 5.30422 10.778 4.76892 11.1324 4.43166C11.4816 4.0993 11.7731 4 12 4V2C11.1223 2 10.3615 2.40438 9.75363 2.98287C9.15092 3.55645 8.65364 4.33918 8.25776 5.2299C7.46489 7.01386 7 9.40829 7 12H9ZM12 4C12.2269 4 12.5184 4.0993 12.8676 4.43166C13.222 4.76892 13.5866 5.30422 13.9146 6.04218C14.5695 7.51558 15 9.62114 15 12H17C17 9.40829 16.5351 7.01386 15.7422 5.2299C15.3464 4.33918 14.8491 3.55645 14.2464 2.98287C13.6385 2.40438 12.8777 2 12 2V4ZM15 12C15 14.3789 14.5695 16.4844 13.9146 17.9578C13.5866 18.6958 13.222 19.2311 12.8676 19.5683C12.5184 19.9007 12.2269 20 12 20V22C12.8777 22 13.6385 21.5956 14.2464 21.0171C14.8491 20.4435 15.3464 19.6608 15.7422 18.7701C16.5351 16.9861 17 14.5917 17 12H15Z"
                                fill="black" />
                        </svg>
                        Mã lớp: {{ $class->malop }}
                        @foreach ($class->quanLyGV as $quanLyGV)
                            <p>Giáo viên: <strong>{{ $quanLyGV->giaoVien->tengv }}</strong></p>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="class-statics">
                <div class="statics-body">
                    <div class="excel-export">
                        Bảng điểm
                        <a href="{{ route('teacher.exportScores', ['malop' => $class->malop]) }}">
                            <button>
                                <div class="excel-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none">
                                        <path
                                            d="M12 0C11.8654 0.000816118 11.7313 0.015212 11.5996 0.0429688L11.5977 0.0410156L1.62891 2.03516L1.62695 2.03711C1.16976 2.12391 0.757141 2.36743 0.460171 2.72572C0.163201 3.084 0.000467831 3.53464 0 4V20C9.94457e-05 20.466 0.162903 20.9173 0.4603 21.276C0.757698 21.6348 1.17102 21.8784 1.62891 21.9648L11.5977 23.959C11.73 23.9862 11.8648 24 12 24C12.5304 24 13.0391 23.7893 13.4142 23.4142C13.7893 23.0391 14 22.5304 14 22V2C14 1.46957 13.7893 0.960859 13.4142 0.585786C13.0391 0.210714 12.5304 0 12 0ZM16 2V5H18V7H16V9H18V11H16V13H18V15H16V17H18V19H16V22H22C23.105 22 24 21.105 24 20V4C24 2.895 23.105 2 22 2H16ZM20 5H21C21.552 5 22 5.448 22 6C22 6.552 21.552 7 21 7H20V5ZM3.18555 7H5.58789L6.83203 9.99023C6.93303 10.2342 7.0138 10.5169 7.0918 10.8379H7.125C7.17 10.6449 7.25853 10.3518 7.39453 9.9668L8.78516 7H10.9727L8.35938 11.9551L11.0508 16.998H8.7168L7.21289 13.7402C7.15589 13.6252 7.0892 13.3933 7.0332 13.0723H7.01172C6.97772 13.2263 6.91059 13.4586 6.80859 13.7676L5.29492 17H2.94922L5.73242 11.9941L3.18555 7ZM20 9H21C21.552 9 22 9.448 22 10C22 10.552 21.552 11 21 11H20V9ZM20 13H21C21.552 13 22 13.448 22 14C22 14.552 21.552 15 21 15H20V13ZM20 17H21C21.552 17 22 17.448 22 18C22 18.552 21.552 19 21 19H20V17Z"
                                            fill="#01B3EF" />
                                    </svg>
                                    Xuất Excel
                                </div>
                            </button>
                        </a>

                    </div>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Tên sinh viên</th>
                                <th>Mã số sinh viên</th>
                                @foreach ($baiKiemTras as $baiKiemTra)
                                    <th>{{ $baiKiemTra->tenbkt }} ({{ $baiKiemTra->tile }}%)</th>
                                @endforeach
                                <th>Điểm trung bình</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($studentsWithResults as $studentData)
                                <tr>
                                    <td>{{ $studentData['sinh_vien']->tensv }}</td>
                                    <td>{{ $studentData['sinh_vien']->mssv }}</td>
                                    @php
                                        $weightedTotal = 0;
                                        $totalWeight = 0;
                                    @endphp
                                    @foreach ($studentData['ket_qua'] as $ketQua)
                                        @if (is_null($ketQua['diem']) || $ketQua['diem'] === '-')
                                            <td>-</td>
                                        @else
                                            <td>{{ $ketQua['diem'] }}</td>
                                            @php
                                                $weightedTotal += floatval($ketQua['diem']) * ($ketQua['tile'] );
                                                $totalWeight += $ketQua['tile'];
                                            @endphp
                                        @endif
                                    @endforeach
                                    <td>
                                        @if ($totalWeight > 0)
                                            {{ round($weightedTotal / $totalWeight, 2) }} <!-- Tính trung bình có trọng số -->
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</Div>

<style>
    .teacher-viewclass {
        display: flex;
        width: 100%;
        height: 100%;
        flex-direction: column;
        align-items: flex-start;
        background: #FFF;
    }

    .heading-dashboard p:nth-child(2) {
        color: #208CE4;
        font-weight: 700;
    }

    .score {
        border-radius: 10px;
        background: #208CE4;
        cursor: pointer;
    }

    .score a {
        color: #FFF;
        font-family: Inter;
        font-weight: 700;
    }

    .score svg path {
        fill: #FFF;
        stroke: #FFF;
    }

    .body {
        display: flex;
        align-items: flex-start;
        flex: 1 0 0;
        align-self: stretch;
        background: #F0F2F5;
    }

    .right {
        display: flex;
        padding: 20px;
        flex-direction: column;
        align-items: flex-start;
        gap: 20px;
        flex: 1 0 0;
        align-self: stretch;
    }

    .class-list {
        display: flex;
        width: 100%;
        flex-direction: column;
        align-items: flex-start;
        gap: 20px;
    }

    .class-name {
        display: flex;
        padding: 20px;
        flex-direction: column;
        align-items: flex-start;
        gap: 20px;
        align-self: stretch;
        border-radius: 10px;
        background: #FFF;
        color: #000;
        font-family: Inter;
        font-size: 20px;
        font-style: normal;
        font-weight: 700;
        line-height: normal;
    }

    .class-id {
        display: flex;
        align-items: center;
        gap: 20px;
        color: #000;
        font-family: Inter;
        font-size: 20px;
        font-style: normal;
        font-weight: 400;
        line-height: normal;
    }

    .class-statics {
        display: flex;
        width: 100%;
        height: auto;
        flex-direction: column;
        align-items: flex-start;
        gap: 20px;
    }

    .statics-body {
        display: flex;
        padding: 20px;
        flex-direction: column;
        align-items: flex-start;
        gap: 20px;
        align-self: stretch;
        border-radius: 10px;
        background: #FFF;
        color: #000;
        font-family: Inter;
        font-size: 20px;
        font-style: normal;
        font-weight: 700;
        line-height: normal;
    }

    .statics-test {
        display: flex;
        padding: 0px 20px;
        justify-content: space-between;
        align-items: flex-start;
        align-self: stretch;
        width: 100%;
    }

    .list-test {
        display: flex;
        width: 500px;
        flex-direction: column;
        align-items: flex-start;
        align-self: stretch;
    }

    .statics-btn {
        display: flex;
        width: 500px;
        flex-direction: column;
        align-items: center;
        align-self: stretch;
    }

    .statics-chart {
        width: 50%;
    }

    .statics-body table {
        width: 100%;
        border-collapse: collapse;
        font-family: 'Inter', sans-serif;
    }

    .statics-body td {
        font-size: 16px;
        font-weight: normal;
        padding: 8px;
        text-align: left;
    }

    .statics-body th {
        font-size: 16px;
        font-weight: bold;
        padding: 8px;
        text-align: left;
        background-color: #208CE4;
        color: #FFF;
    }

    .excel-export {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
    }

    .excel-export button {
        display: inline-flex;
        height: 40px;
        padding: 10px;
        align-items: center;
        gap: 20px;
        border-radius: 10px;
        border: 1px solid rgba(0, 60, 60, 0.20);
        background: #FFF;
        cursor: pointer;
    }

    .excel-btn {
        display: flex;
        align-items: center;
        gap: 10px;
        font-family: "Inter";
        font-size: 16px;
    }

    .excel-export button:hover {
        background-color: #F0F2F5;
    }
</style>
