<link rel="stylesheet" href="{{ asset('css/global.css') }}">
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
            <!-- Đặt tỉ lệ cho danh sách các bài kiểm tra-->
            <form method="POST" action=""
                style="width:100%; flex-direction: column; background-color:#FFF; border-radius: 10px">
                <div class="test-list">
                    @if ($tests->isEmpty())
                        <!-- Hiển thị nếu không có bài kiểm tra -->
                        <p class="text-center">Bạn chưa có bài kiểm tra nào.</p>
                    @else
                        @foreach ($tests as $key => $test)
                            <div class="test-item">
                                <label for="tile-{{ $key }}">{{ $test->tenbkt }}
                                    ({{ $test->danhgia_id }})
                                </label>
                                <input type="number" id="tile-{{ $key }}" name="tile[{{ $key }}]"
                                    min="0" max="100" step="0.01" placeholder="Nhập tỉ lệ">
                            </div>
                        @endforeach
                    @endif
                </div>
                <div class="save-button">
                    <button class="primary" type="submit" style="font-weight: 700; padding: 10px">Lưu</button>
                </div>
            </form>
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

    .test {
        border-radius: 10px;
        background: #208CE4;
        cursor: pointer;
    }

    .test a {
        color: #FFF;
        font-family: Inter;
        font-weight: 700;
    }

    .test svg path {
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

    .class-btn {
        display: flex;
        width: 100%;
        flex-direction: column;
        align-items: flex-start;
        gap: 20px;
        flex-shrink: 0;
        align-self: stretch;
    }

    .btn {
        display: flex;
        padding: 20px;
        flex-direction: column;
        align-items: flex-start;
        gap: 20px;
        flex: 1 0 0;
        align-self: stretch;
        border-radius: 10px;
        background: #FFF;
    }

    .lecturelist-btn {
        display: flex;
        justify-content: space-between;
        align-items: center;
        align-self: stretch;
        height: 40px;
        gap: 20px;
    }

    .class-lectures {
        width: 100%;
        display: flex;
        padding: 0px;
        justify-content: center;
        align-items: flex-start;
        gap: 10px;
        flex: 1 0 0;
        align-self: stretch;
        background: #FFF;
        overflow-y: auto;
    }

    .class-lectures a {
        text-decoration: none;
        color: #000;
    }

    .add-tile {
        background-color: #00b1ff;
        display: flex;
        justify-content: space-between;
        gap: 10px;
        height: 100%;
        align-items: center;
        color: #FFF;
    }

    .add-tile:hover {
        background-color: #208CE4;
    }

    .test-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
        /* Khoảng cách giữa các dòng */

    }

    .test-item {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
        gap: 20px;
    }

    .test-item label {
        font-family: "Inter";
        font-size: 16px;
        font-weight: 400;
        white-space: nowrap;
    }

    .test-item input {
        flex: 1;
        padding: 5px;
        margin-left: 10px;
        font-family: "Inter";
        font-size: 16px;
        border-radius: 5px;
        border: 1px solid rgba(0, 60, 60, 0.2);
        padding: 10px;
    }

    .save-button {
        text-align: right;
        margin-top: 20px;
        display: flex;
    }
</style>
