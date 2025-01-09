<link rel="stylesheet" href="{{ asset('css/global.css') }}">
<Div class="student-viewlecture">
    @include('components.heading')
    <div class="body">
        @include('components.sidebar_2')
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
                    </div>
                </div>
            </div>
            <!-- Danh sách bài giảng -->
            <div class="class-btn">
                <div class="btn">
                    <div class="lecturelist-btn">
                        <input type="text" placeholder="Tìm kiếm">
                    </div>
                    <!-- Hiển thị bảng danh sách bài giảng -->
                    <div class="class-lectures">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Tên bài giảng</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($lectures->isEmpty())
                                    <!-- Kiểm tra nếu không có bài giảng -->
                                    <tr>
                                        <td colspan="3" class="text-center">Bạn chưa có bài giảng nào.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</Div>

<style>
    .student-viewlecture {
        display: flex;
        width: 100%;
        height: 100%;
        flex-direction: column;
        align-items: flex-start;
        background: #FFF;
    }

    .lecture {
        border-radius: 10px;
        background: #208CE4;
        cursor: pointer;
    }

    .lecture a {
        color: #FFF;
        font-family: Inter;
        font-weight: 700;
    }

    .lecture svg path {
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

    .class-btn {
        display: flex;
        width: 100%;
        align-items: flex-start;
        gap: 20px;
        flex: 1 0 0;
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
        padding: 0px 30px;
        justify-content: center;
        align-items: flex-start;
        gap: 10px;
        flex: 1 0 0;
        align-self: stretch;
        background: #FFF;
    }

    /* Table */
    .class-lectures table {
        width: 100%;
        border-collapse: collapse;
        font-family: 'Inter', sans-serif;
    }

    .class-lectures th {
        font-size: 16px;
        font-weight: bold;
        padding: 8px;
        text-align: left;
        background-color: #dedede;
    }

    .class-lectures td {
        font-size: 16px;
        font-weight: normal;
        padding: 8px;
        text-align: left;
    }
</style>
