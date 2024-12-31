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

            <!-- Danh sách bài tập -->
            <div style="width: 100%; background-color: #fff; border-radius: 10px; padding: 20px">
                <div
                    style="width:100%; background-color:#FFF; display:flex; height: 40px; gap: 20px; margin-bottom:20px; margin-top:10px">
                    <button class="primary"
                        onclick="window.location.href='{{ url('teacher/add/test/type/' . $class->malop) }}'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="25" viewBox="0 0 24 25"
                            fill="none">
                            <path
                                d="M8 11.5C7.44772 11.5 7 11.9477 7 12.5C7 13.0523 7.44772 13.5 8 13.5V11.5ZM16 13.5C16.5523 13.5 17 13.0523 17 12.5C17 11.9477 16.5523 11.5 16 11.5V13.5ZM11 16.5C11 17.0523 11.4477 17.5 12 17.5C12.5523 17.5 13 17.0523 13 16.5H11ZM13 8.5C13 7.94772 12.5523 7.5 12 7.5C11.4477 7.5 11 7.94772 11 8.5H13ZM12 20.5C7.58172 20.5 4 16.9183 4 12.5H2C2 18.0228 6.47715 22.5 12 22.5V20.5ZM4 12.5C4 8.08172 7.58172 4.5 12 4.5V2.5C6.47715 2.5 2 6.97715 2 12.5H4ZM12 4.5C16.4183 4.5 20 8.08172 20 12.5H22C22 6.97715 17.5228 2.5 12 2.5V4.5ZM20 12.5C20 16.9183 16.4183 20.5 12 20.5V22.5C17.5228 22.5 22 18.0228 22 12.5H20ZM8 13.5H16V11.5H8V13.5ZM13 16.5V8.5H11V16.5H13Z"
                                fill="white" />
                        </svg>
                        Thêm bài tập
                    </button>
                    <button class="add-tile"
                        onclick="handleClick('{{ url('teacher/add/test/percent/' . $class->malop) }}')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none">
                            <path
                                d="M14 18L21 18M3 18H5M5 18C5 19.3807 6.11929 20.5 7.5 20.5C8.88071 20.5 10 19.3807 10 18C10 16.6193 8.88071 15.5 7.5 15.5C6.11929 15.5 5 16.6193 5 18ZM20 12H21M3 12H10M13 6H21M13 6C13 4.61929 11.8807 3.5 10.5 3.5C9.11929 3.5 8 4.61929 8 6C8 7.38071 9.11929 8.5 10.5 8.5C11.8807 8.5 13 7.38071 13 6ZM3 6H4M16.5 14.5C15.1193 14.5 14 13.3807 14 12C14 10.6193 15.1193 9.5 16.5 9.5C17.8807 9.5 19 10.6193 19 12C19 13.3807 17.8807 14.5 16.5 14.5Z"
                                stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        Đặt tỉ lệ
                    </button>
                </div>
                <div style="width: 100%">
                    @if (!$tests || $tests->isEmpty())
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
                                @if ($test->loai_bkt === 'TracNghiem')
                                    <div class="detail">
                                        <div class="detail-id">
                                            <p style="font-weight:400">
                                                Số lần làm bài:
                                                {{ $test->solanlam ?? 'Không' }}
                                            </p>
                                        </div>
                                    </div>
                                @endif
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
                                <div class="detail">
                                    <div style="width:50px">
                                        <form
                                            action="{{ route('class.delete.test', ['malop' => $class->malop, 'msbkt' => $test->msbkt]) }}"
                                            method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="background: none; border: none; padding: 0;"
                                                onclick="return confirm('Bạn có chắc chắn muốn xóa không?')">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <path
                                                        d="M14 10V17M10 10L10 17M4 6H20M18 6V17.8C18 18.9201 18.0002 19.4802 17.7822 19.908C17.5905 20.2844 17.2841 20.5902 16.9078 20.782C16.48 21 15.9203 21 14.8002 21H9.2002C8.08009 21 7.51962 21 7.0918 20.782C6.71547 20.5902 6.40973 20.2844 6.21799 19.908C6 19.4802 6 18.9201 6 17.8V6H18ZM16 6H8C8 5.06812 8 4.60216 8.15224 4.23462C8.35523 3.74456 8.74432 3.35523 9.23438 3.15224C9.60192 3 10.0681 3 11 3H13C13.9319 3 14.3978 3 14.7654 3.15224C15.2554 3.35523 15.6447 3.74456 15.8477 4.23462C15.9999 4.60216 16 5.06812 16 6Z"
                                                        stroke="#208CE4" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>

                                    <div
                                        style="height: 44px; width: 44px; display: flex; justify-content: center; align-items: center">
                                        <a
                                            href="{{ route('update.lecture', ['malop' => $class->malop, 'id' => $test->msbkt]) }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none">
                                                <path
                                                    d="M21 14C21 13.4477 20.5523 13 20 13C19.4477 13 19 13.4477 19 14H21ZM10.0002 5C10.5525 5 11.0002 4.55228 11.0002 4C11.0002 3.44772 10.5525 3 10.0002 3V5ZM4.21799 5.09204L5.10899 5.54603L4.21799 5.09204ZM5.0918 4.21799L5.54579 5.10899L5.0918 4.21799ZM5.0918 19.782L5.54579 18.891H5.54579L5.0918 19.782ZM4.21799 18.908L3.32698 19.362H3.32698L4.21799 18.908ZM19.7822 18.908L18.8912 18.454L19.7822 18.908ZM18.9078 19.782L18.4538 18.891L18.9078 19.782ZM10 14H9C9 14.5523 9.44772 15 10 15V14ZM10 11L9.29289 10.2929C9.10536 10.4804 9 10.7348 9 11H10ZM19 2L19.7071 1.29289C19.3166 0.902369 18.6834 0.902369 18.2929 1.29289L19 2ZM22 5L22.7071 5.70711C23.0976 5.31658 23.0976 4.68342 22.7071 4.29289L22 5ZM13 14V15C13.2652 15 13.5196 14.8946 13.7071 14.7071L13 14ZM16.7071 4.29289C16.3166 3.90237 15.6834 3.90237 15.2929 4.29289C14.9024 4.68342 14.9024 5.31658 15.2929 5.70711L16.7071 4.29289ZM18.2929 8.70711C18.6834 9.09763 19.3166 9.09763 19.7071 8.70711C20.0976 8.31658 20.0976 7.68342 19.7071 7.29289L18.2929 8.70711ZM19 14V16.8H21V14H19ZM16.8002 19H7.2002V21H16.8002V19ZM5 16.8V7.20001H3V16.8H5ZM7.2002 5H10.0002V3H7.2002V5ZM5 7.20001C5 6.62346 5.00078 6.25119 5.02393 5.96785C5.04612 5.69619 5.0838 5.59548 5.10899 5.54603L3.32698 4.63805C3.13419 5.01643 3.06287 5.40964 3.03057 5.80499C2.99922 6.18866 3 6.65646 3 7.20001H5ZM7.2002 3C6.65663 3 6.18874 2.99922 5.80498 3.03057C5.40962 3.06286 5.01624 3.13416 4.63781 3.32698L5.54579 5.10899C5.59517 5.08383 5.69595 5.04613 5.9678 5.02393C6.25126 5.00078 6.62365 5 7.2002 5V3ZM5.10899 5.54603C5.205 5.35761 5.35788 5.20474 5.54579 5.10899L4.63781 3.32698C4.07306 3.61473 3.61447 4.07382 3.32698 4.63805L5.10899 5.54603ZM7.2002 19C6.62367 19 6.25127 18.9992 5.96782 18.9761C5.69598 18.9538 5.59519 18.9161 5.54579 18.891L4.63781 20.673C5.01623 20.8658 5.40959 20.9371 5.80496 20.9694C6.18873 21.0008 6.65662 21 7.2002 21V19ZM3 16.8C3 17.3436 2.99922 17.8114 3.03057 18.195C3.06287 18.5904 3.13419 18.9836 3.32698 19.362L5.10899 18.454C5.0838 18.4045 5.04612 18.3038 5.02393 18.0322C5.00078 17.7488 5 17.3766 5 16.8H3ZM5.54579 18.891C5.35784 18.7952 5.20498 18.6424 5.10899 18.454L3.32698 19.362C3.61449 19.9262 4.0731 20.3853 4.63781 20.673L5.54579 18.891ZM19 16.8C19 17.3767 18.9993 17.7489 18.9762 18.0323C18.954 18.304 18.9164 18.4046 18.8912 18.454L20.6732 19.362C20.8661 18.9835 20.9373 18.5902 20.9696 18.1949C21.0008 17.8113 21 17.3435 21 16.8H19ZM16.8002 21C17.3438 21 17.8115 21.0008 18.1951 20.9694C18.5904 20.9371 18.9835 20.8657 19.3618 20.673L18.4538 18.891C18.4043 18.9162 18.3036 18.9539 18.0321 18.9761C17.7489 18.9992 17.3767 19 16.8002 19V21ZM18.8912 18.454C18.7956 18.6417 18.6424 18.7949 18.4538 18.891L19.3618 20.673C19.9258 20.3856 20.3854 19.9269 20.6732 19.362L18.8912 18.454ZM11 14V11H9V14H11ZM10.7071 11.7071L19.7071 2.70711L18.2929 1.29289L9.29289 10.2929L10.7071 11.7071ZM18.2929 2.70711L21.2929 5.70711L22.7071 4.29289L19.7071 1.29289L18.2929 2.70711ZM21.2929 4.29289L12.2929 13.2929L13.7071 14.7071L22.7071 5.70711L21.2929 4.29289ZM13 13H10V15H13V13ZM15.2929 5.70711L18.2929 8.70711L19.7071 7.29289L16.7071 4.29289L15.2929 5.70711Z"
                                                    fill="#208CE4" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                                <div class="detail">
                                    <div class="detail-id">
                                        @if ($test->loai_bkt === 'TuLuan')
                                            <a
                                                href="{{ route('grading.list', ['malop' => $class->malop, 'msbkt' => $test->msbkt]) }}">
                                                Chấm điểm</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
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

    .detail-id a {
        text-decoration: none;
        color: #208CE4;
        font-family: "Inter";
        font-size: 16;
        font-weight: 700;
    }
</style>
<script>
    function handleClick(url) {
        const confirmed = confirm("Bạn chỉ được nhập 1 lần duy nhất. Bạn chắc chưa?");
        if (confirmed) {
            window.location.href = url;
        }
    }

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
