<link rel="stylesheet" href="{{ asset('css/global.css') }}">

<div class="student-viewclass">
    @include('components.heading')
    <div class="body">
        <div class="body-sidebar">
            @include('components.sidebar_2')
        </div>
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
            <div style="width: 100%; background-color: #fff; border-radius: 5px; padding: 10px">
                <div style="width: 100%">
                    @if ($tests->isEmpty())
                        <p class="text-center" style="font-family: Inter">Bạn chưa có bài tập nào.</p>
                    @else
                        @foreach ($tests as $key => $test)
                            <!-- Danh sách bài tập -->
                            <div class="test-list" onclick="toggleTestInfo({{ $key }})"
                                style="font-family: Inter; display: flex; align-items: center; gap: 20px">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="24"
                                    viewBox="0 0 30 24" fill="none">
                                    <path
                                        d="M11.25 17H18.75M11.25 14H18.75M23.7495 9H18.2495C17.5494 9 17.1998 8.99998 16.9324 8.89099C16.6972 8.79512 16.5061 8.64218 16.3862 8.45401C16.25 8.2401 16.25 7.96005 16.25 7.4V3M23.75 17.8V9.65399C23.75 9.19048 23.7503 8.95872 23.6877 8.73932C23.6323 8.54475 23.541 8.35788 23.4165 8.18499C23.276 7.99002 23.0791 7.81997 22.6854 7.47986L18.6877 4.02588C18.2503 3.64789 18.0313 3.45888 17.771 3.32343C17.5403 3.20337 17.2858 3.11464 17.0189 3.06077C16.7179 3 16.3965 3 15.7526 3H10.2502C8.85011 3 8.14953 3 7.61475 3.21799C7.14434 3.40973 6.76217 3.71572 6.52248 4.09204C6.25 4.51986 6.25 5.07991 6.25 6.20001V17.8C6.25 18.9201 6.25 19.4801 6.52248 19.908C6.76217 20.2843 7.14434 20.5902 7.61475 20.782C8.14953 21 8.85011 21 10.2502 21H19.7502C21.1504 21 21.85 21 22.3847 20.782C22.8551 20.5902 23.2381 20.2843 23.4778 19.908C23.7503 19.4801 23.75 18.9201 23.75 17.8Z"
                                        stroke="black" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                <div>
                                    <p><strong>Tên bài tập:</strong> {{ $test->tenbkt }}</p>
                                </div>
                            </div>

                            <!-- Chi tiết bài tập -->
                            <div id="test-info-{{ $key }}" class="test-info hidden">
                                <div class="detail">
                                    <div class="detail-id">
                                        <p style="font-weight:400">Thời gian (phút):
                                            {{ $test->thoigianlambai ?? 'Không' }}</p>
                                    </div>
                                </div>
                                <div class="detail">
                                    <div class="detail-id">
                                        <p style="font-weight:400">Ngày bắt đầu:
                                            {{ $test->ngaybatdau ?? 'Không có thông tin' }}</p>
                                    </div>
                                </div>
                                <div class="detail">
                                    <div class="detail-id">
                                        <p style="font-weight:400">Ngày kết thúc:
                                            {{ $test->ngayketthuc ?? 'Không có thông tin' }}</p>
                                    </div>
                                </div>
                                <div class="detail">
                                    <div class="detail-id">
                                        <p style="font-weight:400">Loại bài:
                                            {{ $test->loai_bkt ?? 'Không có thông tin' }}</p>
                                    </div>
                                </div>
                                <div class="detail">
                                    <div class="detail-id">
                                        <p style="font-weight:400">Thành phần đánh giá:
                                            {{ $test->danhgia_id ?? 'Không có thông tin' }}</p>
                                    </div>
                                </div>
                                <div style="width: 200px; height: 40px; margin-left: 10px; margin-top: 10px">
                                    <button class="primary">Làm bài</button>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .heading-dashboard p:nth-child(2) {
        color: #208CE4;
        font-weight: 700;
    }

    .test {
        border-radius: 10px;
        background: #208CE4;
    }

    .test a {
        color: #FFF;
    }

    .test svg path {
        fill: #fff;
    }

    .student-viewclass {
        display: flex;
        width: 100%;
        height: 100%;
        flex-direction: column;
        align-items: flex-start;
        background: #FFF;
    }

    .body {
        display: flex;
        align-items: flex-start;
        flex: 1 0 0;
        align-self: stretch;
        background: #F0F2F5;
        flex-direction: row;
        width: 100%;
        max-width: 100vw;
        overflow: hidden;
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

    .body-sidebar {
        flex-basis: 200px;
    }

    .right {
        flex-grow: 1;
        display: flex;
        padding: 20px;
        flex-direction: column;
        align-items: flex-start;
        gap: 20px;
        flex: 1 0 0;
        align-self: stretch;
        overflow: hidden;
    }

    .class-list {
        display: flex;
        width: 100%;
        max-width: 100%;
        flex-direction: row;
        align-items: flex-start;
        gap: 20px;
        flex: 0 0 auto;
        min-height: 50px;
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
        width: 100%;
        box-sizing: border-box;
    }

    .class-id {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .class-id h1 {
        color: #000;
        font-family: Inter;
        font-size: 20px;
        font-style: normal;
        font-weight: 700;
        line-height: normal;
    }

    .class-id p {
        color: #000;
        font-family: Inter;
        font-size: 20px;
        font-style: normal;
        font-weight: 400;
        line-height: normal;
    }

    .class-list-2 {
        flex-grow: 1;
        display: flex;
        padding: 20px;
        flex-direction: row;
        align-items: flex-start;
        gap: 20px;
        flex: 1 0 0;
        align-self: stretch;
        border-radius: 10px;
        background: #FFF;
    }

    .class-name-2 {
        display: flex;
        width: 800px;
        flex-direction: column;
        align-items: flex-start;
        gap: 20px;
        flex-shrink: 0;
        align-self: stretch;
        background: #FFF;
        flex-grow: 2;
    }

    .search {
        display: flex;
        /* Sử dụng flexbox để căn chỉnh theo chiều ngang */
        align-items: center;
        /* Căn giữa các phần tử theo chiều dọc */
        gap: 20px;
        /* Khoảng cách giữa các phần tử */
        width: 100%;
        /* Chiếm toàn bộ chiều rộng của container */
        border: none;
    }

    .class-btn {
        display: flex;
        /* Sử dụng flexbox để sắp xếp các nút bấm */
        align-items: center;
        /* Căn giữa các nút bấm theo chiều dọc */
        justify-content: flex-start;
        /* Căn lề trái cho các nút bấm */
        gap: 10px;
        /* Khoảng cách giữa các nút */
        width: 100%;
        /* Chiếm toàn bộ chiều rộng của container */
        box-sizing: border-box;
        /* Đảm bảo padding không làm tăng chiều rộng */
        border: none;
    }

    .search-bar {
        flex: 1;
        /* Cho phép nó chiếm không gian còn lại trong class-btn */

    }

    .filter-search {
        display: flex;
        /* Sử dụng flexbox để căn chỉnh các thành phần bên trong */
        align-items: center;
        /* Căn giữa theo chiều dọc */
        gap: 10px;
        /* Khoảng cách giữa các phần tử con */
    }

    .test-list {
        cursor: pointer;
        padding: 10px;
        border: 1px solid #208CE4;
        margin-bottom: 5px;
        border-radius: 5px;
    }

    .test-list:hover {
        background-color: #eaeaea;
    }

    .test-info {
        padding: 10px;
        border-left: 4px solid #007bff;
        margin-bottom: 10px;
    }

    .hidden {
        display: none;
    }


    .test-list:hover {
        background-color: #f0f8ff;
    }

    .test-list-id {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .test-list-id p {
        color: #208CE4;
        font-family: Inter;
        font-size: 20px;
        font-style: normal;
        font-weight: 700;
        line-height: normal;
    }

    .class-name-3 {
        display: flex;
        padding: 10px;
        flex-direction: column;
        align-items: flex-start;
        gap: 0px;
        align-self: stretch;
        background: #FFF;
        flex: 1 1 auto;
    }

    .test-name {
        display: flex;
        padding: 10px;
        align-items: flex-start;
        gap: 20px;
        align-self: stretch;
        border-radius: 10px;
        border: 1px solid #208CE4;
        background: #FFF;
    }

    .test-name p {
        color: #208CE4;
        font-family: Inter;
        font-size: 16px;
        font-style: normal;
        font-weight: 700;
        line-height: normal;
    }

    .detail {
        display: flex;
        padding: 10px;
        align-items: flex-start;
        gap: 5px;
        align-self: stretch;
        cursor: default;
    }

    .detail-id {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .detail p {
        color: #000;
        font-family: Inter;
        font-size: 16px;
        font-style: normal;
        font-weight: 700;
        line-height: normal;
    }

    .do-btn {
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .hidden {
        display: none;
    }

    .test-info {
        margin-top: 10px;
    }
</style>

<script>
    function toggleTestInfo(id) {
        const infoDiv = document.getElementById(`test-info-${id}`);

        // Kiểm tra trạng thái hiển thị
        if (infoDiv.classList.contains('hidden')) {
            // Ẩn tất cả các test-info khác trước khi hiển thị
            document.querySelectorAll('.test-info').forEach((div) => {
                div.classList.add('hidden');
            });

            // Hiển thị div hiện tại
            infoDiv.classList.remove('hidden');
        } else {
            // Ẩn nếu đang mở
            infoDiv.classList.add('hidden');
        }
    }
</script>
