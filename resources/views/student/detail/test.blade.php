<html lang="vi">
<!DOCTYPE html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Detail Test</title>
</head>

<div class="student-dotest">
    <div class="body">
        <div class="left">
            <div class="content">
                @if ($test->file_path)
                    @php
                        $filePath = $test->file_path;
                        $extension = pathinfo($filePath, PATHINFO_EXTENSION); // Lấy đuôi file
                    @endphp

                    @if ($extension == 'pdf')
                        <!-- Hiển thị file PDF -->
                        <embed src="{{ asset($filePath) }}" width="100%" height="650px" type="application/pdf">
                    @elseif(in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'bmp']))
                        <!-- Hiển thị ảnh -->
                        <img src="{{ asset($filePath) }}" alt="Image"
                            style="width: 100%; max-height: 650px; object-fit: contain;">
                    @else
                        <p>Không hỗ trợ hiển thị file này.</p>
                    @endif
                @else
                    <p>Không có file để hiển thị.</p>
                @endif
            </div>
        </div>

        <div class="right">
            <div class="class-list">
                <div class="class-name">
                    <p>{{ $test->tenbkt }}</p>
                </div>
            </div>
            <div style="padding: 20px">
                <h3>Danh sách thông tin lần làm bài:</h3>
                <ul>
                    @foreach ($testResults as $result)
                        <li>
                            <h4>Sinh viên: {{ $result->tensv }} (MSSV: {{ $result->mssv }})</h4>
                            <p>Số lần làm bài: {{ $result->solanlam }}</p>
                            <p>Điểm: {{ $result->diem }}</p>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    html,
    body {
        height: 100%;
        overflow: hidden;
        font-family: Inter, sans-serif;
    }

    .student-dotest {
        display: flex;
        width: 100%;
        height: 100vh;
    }

    .body {
        display: flex;
        width: 100%;
        height: 100%;
        background: #F0F2F5;
    }

    .left {
        width: 70%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
        background-color: #fff;
    }

    .content {
        width: 100%;
        height: 100%;
        background-color: #f3f3f3;
        overflow: auto;
        padding: 30px;
    }

    .right {
        width: 500px;
        flex-basis: 30%;
        display: flex;
        flex-direction: column;
        background: #FFF;
        height: 100%;
        padding: 0;
        overflow-y: auto;
    }

    .class-list {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        align-self: stretch;
    }

    .class-name {
        background: #208CE4;
        padding: 20px;
        color: #FFFFFF;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 20px;
        flex: 1 0 0;
        width: 100%;
        font-weight: 700;
        font-size: 18px;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
    }

    ul {
        list-style-type: none;
    }

    ul li {
        gap: 20px;
    }

    ul li h4 {
        font-weight: 400;
        margin-bottom: 20px;
        margin-top: 20px;
    }

    ul li p {
        margin-bottom: 20px;
    }
</style>
</html>