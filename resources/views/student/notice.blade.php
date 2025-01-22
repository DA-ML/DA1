<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Student Notice</title>
</head>

<div class="student-notice">
    @include('components.heading')
    <div class="body">
        <div class="class-statics">
            <div class="statics-body">
                <h1>Các thông báo</h1>
                @if ($notices->isEmpty())
                    <p>Không có thông báo nào.</p>
                @else
                    <ul>
                        @foreach ($notices as $notice)
                            <li>
                                <div class="notice-message">{{ $notice->message }}</div>
                                <div class="notice-created-at">Được tạo lúc: {{ $notice->created_at }}</div>
                                <div class="notice-updated-at">Được cập nhật lúc: {{ $notice->updated_at }}</div>
                                <div class="notice-detail">
                                    @if($notice->baiKiemTra)
                                        <a href="{{ route('student.detail.essay', ['malop' => $notice->baiKiemTra->malop, 'msbkt' => $notice->baiKiemTra->msbkt]) }}" class="view-detail-link">Xem chi tiết</a>
                                    @else
                                        <span>Thông tin bài kiểm tra không có sẵn</span>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
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
        color: #208CE4;
        text-decoration: none;
        font-weight: bold;
    }

    .view-detail-link:hover {
        color: #004b9a;
        text-decoration: underline
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
        padding: 10px 20px;
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
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

    .created-at, .updated-at {
        font-size: 14px;
        font-style: italic;
        font-weight: 400;
        margin-top: 0;
        padding-top: 0;
        line-height: 1;
        margin-bottom: 0px;
    }

    ul {
        list-style-type: none;
        padding: 0;
        margin: 0;
        width: 100%;
    }

    li {
        margin-bottom: 20px;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        width: 100%;
        box-sizing: border-box;
    }

    .notice-message {
        font-weight: bold;
        margin-bottom: 5px;
    }

    .notice-created-at,
    .notice-updated-at {
        font-size: 14px;
        color: #666;
        margin-bottom: 5px;
    }

    .notice-detail {
        margin-top: 10px;
    }

    .notice-message,
    .notice-created-at,
    .notice-updated-at,
    .notice-detail {
        display: block;
        width: 100%;
        margin-bottom: 10px;
    }
</style>
</html>
