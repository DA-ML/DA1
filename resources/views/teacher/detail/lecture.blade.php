<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Teacher Lecture</title>
</head>

<Div class="teacher-viewclass">
    @include('components.heading')
    <div class="body">
        @include('components.sidebar')
        <div class="right">
            <div class="class-statics">
                <div class="lecture-file">
                    {{-- Links --}}
                    @if ($lecture->file_paths)
                        @php
                            // Giải mã JSON để lấy mảng các đường dẫn
                            $filePaths = json_decode($lecture->file_paths);

                            // Kiểm tra nếu mảng không rỗng
                            if (is_array($filePaths) && count($filePaths) > 0) {
                                $filePath = str_replace('\\/', '/', $filePaths[0]); // Lấy file đầu tiên và xử lý đường dẫn
                                $extension = pathinfo($filePath, PATHINFO_EXTENSION); // Lấy đuôi file
                            }
                        @endphp

                        @if (!empty($filePath))
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

                            <!-- Liên kết tải về -->
                            <p><a href="{{ asset($filePath) }}" download>Click vào đây để tải file</a></p>
                        @else
                            <p>Không có file để hiển thị.</p>
                        @endif
                    @else
                        <p>Không có file để hiển thị.</p>
                    @endif
                </div>
                <div class="lecture-info">
                    <p style="font-weight: 700; font-size:20px">Thông tin bài giảng</p>
                    <p><strong>Tên bài giảng:</strong> {{ $lecture->tenbg }}</p>
                    <p><strong>Mô tả:</strong></p>
                    <p>{{ $lecture->noidungbg }}</p>
                    <a href="{{ asset($lecture->link_paths) }}">Links bài giảng</a>
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
        align-items: flex-start;
        flex: 1 0 0;
        gap: 20px;
        background-color: white;
        padding: 20px;
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

    .statics-body h3 {
        color: #000;
        font-family: Inter;
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: normal;
    }

    .statics-body h1 {
        color: #000;
        font-family: Inter;
        font-size: 16px;
        font-style: normal;
        font-weight: 700;
        line-height: normal;
    }

    #classLink {
        font-weight: 700;
        color: #208CE4;
    }

    .lecture-file {
        display: flex;
        width: 70%;
        padding: 20px;
        flex-direction: column;
        align-items: flex-start;
        gap: 20px;
        flex-shrink: 0;
        align-self: stretch;
    }

    .lecture-info {
        display: flex;
        padding: 20px;
        flex-direction: column;
        align-items: flex-start;
        gap: 20px;
        flex: 1 0 0;
        align-self: stretch;
        border: 1px solid #B9B9B9;
        background: #FFF;
    }

    .lecture-info p {
        color: #000;
        font-family: Inter;
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: normal;
    }

    .lecture-info a {
        text-decoration: none;
        color: #208CE4;
        font-family: "Inter";
        font-weight: 700;
    }
</style>
