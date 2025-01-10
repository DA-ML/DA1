<html lang="vi">
<!DOCTYPE html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Student Dashboard</title>
</head>

<div class="dashboard">
    @include('components.heading')
    <div class="body">
        <div class="right">
            <div class="class_list">
                <p>Lớp học:</p>
                <div class="container">
                    <p><strong>Học kỳ: {{ $currentSemester }}, năm học: {{ $currentYear }}</strong></p>
                    @if ($classes->isEmpty())
                        <p>Bạn không có lớp học nào trong học kỳ này.</p>
                    @else
                        <ul>
                            @foreach ($classes as $class)
                                <li>
                                    <p><strong>Tên lớp:</strong> {{ $class->tenlop }} - <strong>Mã lớp:
                                        </strong>{{ $class->malop }}</p>
                                    <p><strong>Khoa:</strong> {{ $class->tenkhoa }} </p>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
            <div class="class_list">
                <p>Bài giảng:</p>
                <div class="container">
                    @php
                        $hasLecture = false;
                    @endphp

                    @foreach ($lectures as $item)
                        @if ($item->tenbg)
                            @php
                                $hasLecture = true;
                            @endphp
                            <ul>
                                <li>
                                    <strong>Bài giảng:</strong> {{ $item->tenbg }}
                                </li>
                            </ul>
                        @endif
                    @endforeach

                    @if (!$hasLecture)
                        <p>Bạn chưa có bài giảng nào.</p>
                    @endif
                </div>
            </div>
            <div class="class_list">
                <p>Bài tập:</p>
                <div class="container">
                    @php
                        $hasExam = false;
                    @endphp

                    @foreach ($exams as $item)
                        @if ($item->tenbkt)
                            @php
                                $hasExam = true;
                            @endphp
                            <div>
                                <p><strong>Bài kiểm tra: </strong> {{ $item->tenbkt }}</p>
                                <p><strong>Loại: </strong> {{ $item->loai_bkt }}</p>
                                <p><strong>Số câu hỏi: </strong> {{ $item->num_ques }}</p>
                                <p><strong>Bắt đầu: </strong> {{ $item->ngaybatdau }}</p>
                                <p><strong>Kết thúc: </strong> {{ $item->ngayketthuc }}</p>
                            </div>
                        @endif
                    @endforeach

                    @if (!$hasExam)
                        <p>Bạn chưa có bài tập nào</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .dashboard {
        display: flex;
        width: 100%;
        height: 100%;
        flex-direction: column;
        align-items: flex-start;
        background: #FFF;
    }

    .heading-dashboard p:nth-child(1) {
        color: #208CE4;
        font-style: normal;
        font-weight: 700;
        line-height: normal;
    }

    .body {
        display: flex;
        align-items: flex-start;
        flex: 1 0 0;
        align-self: stretch;
        background: #F0F2F5;
        overflow: hidden;
    }

    .right {
        display: flex;
        padding: 20px;
        flex-direction: column;
        align-items: flex-start;
        gap: 20px;
        flex: 1 0 0;
        align-self: stretch;
        overflow-y: auto;
    }

    .class_list {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 20px;
        align-self: stretch;
    }

    .class_list p {
        color: #000;
        font-family: Inter;
        font-size: 20px;
        font-style: normal;
        font-weight: 700;
        line-height: normal;
    }

    .container {
        display: flex;
        height: auto;
        gap: 10px;
        border-radius: 10px;
        background: #FFF;
        padding: 20px;
        flex-direction: column;
        align-items: flex-start;
        flex: 1 0 0;
        align-self: stretch;
    }

    .container p {
        font-family: "Inter";
        font-weight: 400;
        font-size: 16px;
        margin-bottom: 10px;
    }

    .container ul {
        font-family: "Inter";
        list-style: none;
    }

    .container ul li {
        font-size: 16px;
    }

    .container ul li p {
        font-size: 16px;
        margin-bottom: 10px;
    }
</style>
</html>