<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Teacher View Lecture</title>
</head>

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
            <!-- Danh sách bài giảng -->
            <div class="class-btn">
                <div class="btn">
                    <div class="lecturelist-btn">
                        @include('components.search_bar')
                        <button class="primary"
                            onclick="window.location.href='{{ url('teacher/add/lecture/' . $class->malop) }}'">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="25" viewBox="0 0 24 25"
                                fill="none">
                                <path
                                    d="M8 11.5C7.44772 11.5 7 11.9477 7 12.5C7 13.0523 7.44772 13.5 8 13.5V11.5ZM16 13.5C16.5523 13.5 17 13.0523 17 12.5C17 11.9477 16.5523 11.5 16 11.5V13.5ZM11 16.5C11 17.0523 11.4477 17.5 12 17.5C12.5523 17.5 13 17.0523 13 16.5H11ZM13 8.5C13 7.94772 12.5523 7.5 12 7.5C11.4477 7.5 11 7.94772 11 8.5H13ZM12 20.5C7.58172 20.5 4 16.9183 4 12.5H2C2 18.0228 6.47715 22.5 12 22.5V20.5ZM4 12.5C4 8.08172 7.58172 4.5 12 4.5V2.5C6.47715 2.5 2 6.97715 2 12.5H4ZM12 4.5C16.4183 4.5 20 8.08172 20 12.5H22C22 6.97715 17.5228 2.5 12 2.5V4.5ZM20 12.5C20 16.9183 16.4183 20.5 12 20.5V22.5C17.5228 22.5 22 18.0228 22 12.5H20ZM8 13.5H16V11.5H8V13.5ZM13 16.5V8.5H11V16.5H13Z"
                                    fill="white" />
                            </svg>
                            Thêm bài giảng
                        </button>
                    </div>
                    <!-- Hiển thị bảng danh sách bài giảng -->
                    <div class="class-lectures">
                        <table class="table table-striped" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Tên bài giảng</th>
                                    <th>Cập nhật</th>
                                    <th>Xóa</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($lectures->isEmpty())
                                    <!-- Kiểm tra nếu không có bài giảng -->
                                    <tr>
                                        <td colspan="4" class="text-center">Bạn chưa có bài giảng nào.</td>
                                        <!-- Hiển thị thông báo -->
                                    </tr>
                                @else
                                    @foreach ($lectures as $key => $lecture)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                <a style="text-decoration: none; color: #000"
                                                    href="{{ route('lecture.detail', ['malop' => $lecture->malop, 'id' => $lecture->msbg]) }}">
                                                    {{ $lecture->tenbg }}
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ route('lecture.edit', ['malop' => $class->malop, 'id' => $lecture->msbg]) }}"
                                                    class="btn btn-warning" title = "Sửa">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                        height="24" viewBox="0 0 24 24" fill="none">
                                                        <path
                                                            d="M21 14C21 13.4477 20.5523 13 20 13C19.4477 13 19 13.4477 19 14H21ZM10.0002 5C10.5525 5 11.0002 4.55228 11.0002 4C11.0002 3.44772 10.5525 3 10.0002 3V5ZM4.21799 5.09204L5.10899 5.54603L4.21799 5.09204ZM5.0918 4.21799L5.54579 5.10899L5.0918 4.21799ZM5.0918 19.782L5.54579 18.891H5.54579L5.0918 19.782ZM4.21799 18.908L3.32698 19.362H3.32698L4.21799 18.908ZM19.7822 18.908L18.8912 18.454L19.7822 18.908ZM18.9078 19.782L18.4538 18.891L18.9078 19.782ZM10 14H9C9 14.5523 9.44772 15 10 15V14ZM10 11L9.29289 10.2929C9.10536 10.4804 9 10.7348 9 11H10ZM19 2L19.7071 1.29289C19.3166 0.902369 18.6834 0.902369 18.2929 1.29289L19 2ZM22 5L22.7071 5.70711C23.0976 5.31658 23.0976 4.68342 22.7071 4.29289L22 5ZM13 14V15C13.2652 15 13.5196 14.8946 13.7071 14.7071L13 14ZM16.7071 4.29289C16.3166 3.90237 15.6834 3.90237 15.2929 4.29289C14.9024 4.68342 14.9024 5.31658 15.2929 5.70711L16.7071 4.29289ZM18.2929 8.70711C18.6834 9.09763 19.3166 9.09763 19.7071 8.70711C20.0976 8.31658 20.0976 7.68342 19.7071 7.29289L18.2929 8.70711ZM19 14V16.8H21V14H19ZM16.8002 19H7.2002V21H16.8002V19ZM5 16.8V7.20001H3V16.8H5ZM7.2002 5H10.0002V3H7.2002V5ZM5 7.20001C5 6.62346 5.00078 6.25119 5.02393 5.96785C5.04612 5.69619 5.0838 5.59548 5.10899 5.54603L3.32698 4.63805C3.13419 5.01643 3.06287 5.40964 3.03057 5.80499C2.99922 6.18866 3 6.65646 3 7.20001H5ZM7.2002 3C6.65663 3 6.18874 2.99922 5.80498 3.03057C5.40962 3.06286 5.01624 3.13416 4.63781 3.32698L5.54579 5.10899C5.59517 5.08383 5.69595 5.04613 5.9678 5.02393C6.25126 5.00078 6.62365 5 7.2002 5V3ZM5.10899 5.54603C5.205 5.35761 5.35788 5.20474 5.54579 5.10899L4.63781 3.32698C4.07306 3.61473 3.61447 4.07382 3.32698 4.63805L5.10899 5.54603ZM7.2002 19C6.62367 19 6.25127 18.9992 5.96782 18.9761C5.69598 18.9538 5.59519 18.9161 5.54579 18.891L4.63781 20.673C5.01623 20.8658 5.40959 20.9371 5.80496 20.9694C6.18873 21.0008 6.65662 21 7.2002 21V19ZM3 16.8C3 17.3436 2.99922 17.8114 3.03057 18.195C3.06287 18.5904 3.13419 18.9836 3.32698 19.362L5.10899 18.454C5.0838 18.4045 5.04612 18.3038 5.02393 18.0322C5.00078 17.7488 5 17.3766 5 16.8H3ZM5.54579 18.891C5.35784 18.7952 5.20498 18.6424 5.10899 18.454L3.32698 19.362C3.61449 19.9262 4.0731 20.3853 4.63781 20.673L5.54579 18.891ZM19 16.8C19 17.3767 18.9993 17.7489 18.9762 18.0323C18.954 18.304 18.9164 18.4046 18.8912 18.454L20.6732 19.362C20.8661 18.9835 20.9373 18.5902 20.9696 18.1949C21.0008 17.8113 21 17.3435 21 16.8H19ZM16.8002 21C17.3438 21 17.8115 21.0008 18.1951 20.9694C18.5904 20.9371 18.9835 20.8657 19.3618 20.673L18.4538 18.891C18.4043 18.9162 18.3036 18.9539 18.0321 18.9761C17.7489 18.9992 17.3767 19 16.8002 19V21ZM18.8912 18.454C18.7956 18.6417 18.6424 18.7949 18.4538 18.891L19.3618 20.673C19.9258 20.3856 20.3854 19.9269 20.6732 19.362L18.8912 18.454ZM11 14V11H9V14H11ZM10.7071 11.7071L19.7071 2.70711L18.2929 1.29289L9.29289 10.2929L10.7071 11.7071ZM18.2929 2.70711L21.2929 5.70711L22.7071 4.29289L19.7071 1.29289L18.2929 2.70711ZM21.2929 4.29289L12.2929 13.2929L13.7071 14.7071L22.7071 5.70711L21.2929 4.29289ZM13 13H10V15H13V13ZM15.2929 5.70711L18.2929 8.70711L19.7071 7.29289L16.7071 4.29289L15.2929 5.70711Z"
                                                            fill="#208CE4" />
                                                    </svg>
                                                </a>
                                            </td>
                                            <td>
                                                <form
                                                    action="{{ route('class.delete.lecture', ['malop' => $class->malop, 'id' => $lecture->msbg]) }}"
                                                    method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        style="background: none; border: none; padding: 0;" title = "Xóa"
                                                        onclick="return confirm('Bạn có chắc chắn muốn xóa không?')">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none">
                                                            <path
                                                                d="M14 10V17M10 10L10 17M4 6H20M18 6V17.8C18 18.9201 18.0002 19.4802 17.7822 19.908C17.5905 20.2844 17.2841 20.5902 16.9078 20.782C16.48 21 15.9203 21 14.8002 21H9.2002C8.08009 21 7.51962 21 7.0918 20.782C6.71547 20.5902 6.40973 20.2844 6.21799 19.908C6 19.4802 6 18.9201 6 17.8V6H18ZM16 6H8C8 5.06812 8 4.60216 8.15224 4.23462C8.35523 3.74456 8.74432 3.35523 9.23438 3.15224C9.60192 3 10.0681 3 11 3H13C13.9319 3 14.3978 3 14.7654 3.15224C15.2554 3.35523 15.6447 3.74456 15.8477 4.23462C15.9999 4.60216 16 5.06812 16 6Z"
                                                                stroke="#208CE4" stroke-width="2" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </td>

                                        </tr>
                                    @endforeach
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
        padding: 0px;
        justify-content: center;
        align-items: flex-start;
        gap: 10px;
        flex: 1 0 0;
        align-self: stretch;
        background: #FFF;
        overflow-y: auto;
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
        text-align: left;
        background-color: #208CE4;
        padding: 8px;
        color: white;
    }

    .class-lectures td {
        font-size: 16px;
        font-weight: normal;
        text-align: left;
        padding: 8px;
    }

    .class-lectures a {
        text-decoration: none;
        color: #000;
    }
</style>
