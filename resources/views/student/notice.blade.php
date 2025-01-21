<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Student Notice</title>
</head>

<div class="student-notice">
    @include('components.heading')
    <div class="body">
        <h1>Các thông báo</h1>
        @if ($notices->isEmpty())
            <p>Không có thông báo nào.</p>
        @else
        <ul>
            @foreach ($notices as $notice)
                <li>
                    {{ $notice->message }}
                    @if($notice->baiKiemTra)
                        <a href="{{ route('student.detail.essay', ['malop' => $notice->baiKiemTra->malop, 'msbkt' => $notice->baiKiemTra->msbkt]) }}" class="view-detail-link">Xem chi tiết</a>
                    @else
                        <span>Thông tin bài kiểm tra không có sẵn</span>
                    @endif
                </li>
            @endforeach
        </ul>
        @endif
    </div>
</div>

<style>
    .student-notice {
        display: flex;
        width: 100%;
        height: 100%;
        flex-direction: column;
        align-items: flex-start;
        background: #FFF;
    }

    .body {
        display: flex;
        width: 100%;
        height: 100%;
        flex-direction: column;
        align-items: flex-start;
        background: #FFF;
        padding: 20px;
    }

    .body h1 {
        color: #000;
        font-family: Inter;
        font-size: 30px;
        font-style: normal;
        font-weight: 700;
        line-height: normal;
    }

    ul {
        list-style-type: none;
        padding: 0;
    }

    li {
        color: #000;
        font-family: Inter;
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: normal;
        margin: 1rem 0;
    }

    .view-detail-link {
        color: blue;
    }

    .view-detail-link:hover {
        color: #004b9a;
    }

</style>
</html>
