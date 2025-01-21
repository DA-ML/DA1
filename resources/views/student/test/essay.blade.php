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
                        <a href="{{ asset($filePath) }}" target="_blank" style="text-decoration: none; color: #208CE4;">
                            Mở hoặc tải file
                        </a>
                    @elseif(in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'bmp']))
                        <!-- Hiển thị ảnh -->
                        <img src="{{ asset($filePath) }}" alt="Image"
                            style="width: 100%; max-height: 650px; object-fit: contain;">
                    @else
                        <!-- Hiển thị link tải file -->
                        <p>Tệp đính kèm không hiển thị được. Bạn có thể tải xuống:</p>
                        <a href="{{ asset($filePath) }}" download style="text-decoration: none; color: #208CE4;">
                            Tải file về
                        </a>
                    @endif
                @else
                    <p>Không có file để hiển thị.</p>
                @endif
            </div>
        </div>

        <div class="right">
            <form id="submitForm" action="{{ route('student.test.storeEssay', ['malop' => $malop]) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="msbkt" value="{{ $test->msbkt }}">
                <input type="hidden" name="mssv" value="{{ $mssv }}">
                <div class="class-list-2">
                    <div class="class-name-2">
                        <div class="test-name">
                            <p>{{ $test->tenbkt }}</p>
                        </div>
                        <div style="align-items: center; width: 100%">
                            <label for="file-upload" style="display: block; font-weight: bold; margin-bottom: 10px;">
                                Chỉ hỗ trợ tệp dưới dạng PDF hoặc ảnh (jpg, jpeg, png)
                            </label>
                            <input type="file" name="file" id="file" accept=".pdf,image/*" required title="Tên file được tải lên sẽ hiển thị ở đây">
                        </div>
                    </div>
                </div>
                <div class="button-group">
                    <button class="submit-btn" type="button" onclick="confirmSubmit()">SUBMIT</button>
                </div>
            </form>
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

    .class-list-2 {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
        width: 100%;
    }

    .class-name-2 {
        display: flex;
        flex-direction: column;
        gap: 15px;
        padding: 10px;
        flex-grow: 1;
        overflow-y: auto;
        box-sizing: border-box;
        width: 100%;
    }

    .test-name {
        margin-bottom: 20px;
        text-align: left;
        font-weight: 600;
        font-size: 16px;
        padding: 15px;
        border: 1px solid #e0e0e0;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        background: #FFF;
    }

    .button-group {
        display: flex;
        margin-top: auto;
        justify-content: center;
        margin-bottom: 10px;
        gap: 10px;
    }

    .submit-btn {
        background: #208CE4;
        font-family: "Inter";
        font-size: 16px;
        padding: 10px;
        color: #FFF;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .submit-btn:hover {
        background-color: #004b9a;
    }
</style>
<script>
    function confirmSubmit() {
        if (confirm("Bạn có chắc chắn muốn gửi không?")) {
            document.getElementById("submitForm").submit()
        } else {
            console.log("Người dùng đã hủy.");
        }
    }
</script>
</html>
