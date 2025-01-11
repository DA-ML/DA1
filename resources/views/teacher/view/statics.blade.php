<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Teacher Statics</title>
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
                    Báo cáo
                    <div class="test-setting">
                        <div style="display:flex">
                            <button type="button" onclick="openCity('London', this)" data-tab-button class="active">
                                Bài kiểm tra
                            </button>
                            <button type="button" onclick="openCity('Paris', this)" data-tab-button>
                                Thành phần đánh giá
                            </button>
                        </div>

                        <div id="London" data-city="true" style="width: 100%">
                            <div style="display: flex; width:100%">
                                <div id="piechart" style="width: 900px; height: 500px;"></div>
                                <div class="statics-rank">
                                    <h1>BẢNG XẾP HẠNG</h1>
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>STT</th>
                                                <th>Tên</th>
                                                <th>MSSV</th>
                                                <th>Điểm trung bình</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($leaderboard as $student)
                                                <tr>
                                                    <td>{{ $student['stt'] }}</td>
                                                    <td>{{ $student['tensv'] }}</td>
                                                    <td>{{ $student['mssv'] }}</td>
                                                    <td>{{ number_format($student['diem_tb'], 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="Paris" data-city="true" style="display:none; width: 100%">
                        <div style="width: 100%">
                            <h1 style="font-size:16">Thành phần đánh giá: A1</h1>
                            <div id="columnchart_material" style="width: 100%; height: auto"></div>
                            <h1 style="font-size:16">Thành phần đánh giá: A3</h1>
                            <div id="columnchart_materialA3" style="width: 100%; height: auto"></div>
                            <h1 style="font-size:16">Thành phần đánh giá: A4</h1>
                            <div id="columnchart_materialA4" style="width: 100%; height: auto"></div>
                        </div>
                    </div>
                </div>
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
        overflow: hidden;
    }

    .heading-dashboard p:nth-child(2) {
        color: #208CE4;
        font-weight: 700;
    }

    .statics {
        border-radius: 10px;
        background: #208CE4;
        cursor: pointer;
    }

    .statics a {
        color: #FFF;
        font-family: Inter;
        font-weight: 700;
    }

    .statics svg path {
        fill: #FFF;
        stroke: #FFF;
    }

    .body {
        display: flex;
        align-items: flex-start;
        flex: 1 0 0;
        align-self: stretch;
        background: #F0F2F5;
        overflow-y: auto;
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

    .test-setting {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        flex: 1 0 0;
        align-self: stretch;
        background: #FFF;
        width: 100%;
    }

    .test-setting button {
        color: #000;
        font-family: Inter;
        font-size: 16px;
        font-style: normal;
        font-weight: 700;
        line-height: normal;
    }

    [data-tab-button] {
        background-color: #fafafa;
        color: #000;
        border-radius: 0;
        border: none;
        padding: 10px 20px;
        cursor: pointer;
    }

    [data-tab-button].active {
        background-color: #208ce4;
        color: #fff;
    }

    .statics-rank {
        display: flex;
        padding: 20px;
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
        align-self: stretch;
    }

    .statics-rank h1 {
        color: #000;
        text-align: center;
        font-family: Inter;
        font-size: 24px;
        font-style: normal;
        font-weight: 700;
        line-height: normal;
    }

    .statics-rank table {
        width: 100%;
        border-collapse: collapse;
        font-family: 'Inter', sans-serif;
    }

    .statics-rank th {
        font-size: 16px;
        font-weight: bold;
        text-align: left;
        background-color: #208CE4;
        padding: 8px;
        color: white;
    }

    .statics-rank td {
        font-size: 16px;
        font-weight: normal;
        text-align: left;
        padding: 8px;
    }
</style>

<script>
    function openCity(cityName, button) {
        // Ẩn tất cả các tab
        var tabs = document.querySelectorAll('[data-city="true"]');
        for (var i = 0; i < tabs.length; i++) {
            tabs[i].style.display = "none";
        }
        // Hiển thị tab được chọn
        document.getElementById(cityName).style.display = "block";

        // Loại bỏ class active khỏi tất cả các nút
        var buttons = document.querySelectorAll('[data-tab-button]');
        buttons.forEach(btn => btn.classList.remove('active'));

        // Thêm class active vào nút hiện tại
        button.classList.add('active');
    }
</script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {
        'packages': ['corechart']
    });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Loại', 'Số lượng'],
            @foreach ($scoreStatistics as $stat)
                ['{{ $stat->category }}', {{ $stat->count }}],
            @endforeach
        ]);

        var options = {
            title: 'Học lực sinh viên'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
    }
</script>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {
        packages: ['corechart', 'bar']
    });

    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        const chartData = @json($chartData);

        const data = google.visualization.arrayToDataTable(chartData);

        const options = {
            chart: {
                title: 'Thống Kê Thành Phần A1',
                subtitle: 'Giới, Khá, Trung Bình, Yếu'
            },
            bars: 'vertical',
            hAxis: {
                title: 'Chuẩn Đầu Ra'
            },
            vAxis: {
                title: 'Số lượng'
            }
        };

        const chart = new google.visualization.ColumnChart(document.getElementById('columnchart_material'));
        chart.draw(data, options);
    }
</script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {
        packages: ['corechart', 'bar']
    });

    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        const chartData = @json($chartDataA3);

        const data = google.visualization.arrayToDataTable(chartData);

        const options = {
            chart: {
                title: 'Thống Kê Thành Phần A3',
                subtitle: 'Giới, Khá, Trung Bình, Yếu'
            },
            bars: 'vertical',
            hAxis: {
                title: 'Chuẩn Đầu Ra'
            },
            vAxis: {
                title: 'Số lượng'
            }
        };

        const chart = new google.visualization.ColumnChart(document.getElementById('columnchart_materialA3'));
        chart.draw(data, options);
    }
</script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {
        packages: ['corechart', 'bar']
    });

    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        const chartData = @json($chartDataA4);

        const data = google.visualization.arrayToDataTable(chartData);

        const options = {
            chart: {
                title: 'Thống Kê Thành Phần A4',
                subtitle: 'Giới, Khá, Trung Bình, Yếu'
            },
            bars: 'vertical',
            hAxis: {
                title: 'Chuẩn Đầu Ra'
            },
            vAxis: {
                title: 'Số lượng'
            }
        };

        const chart = new google.visualization.ColumnChart(document.getElementById('columnchart_materialA4'));
        chart.draw(data, options);
    }
</script>
