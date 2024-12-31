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
                    <div class="time">
                        <p>Thời gian còn lại: <span id="countdown"
                                style="font-weight: 700">{{ $test->thoigianlambai }}</span> phút</p>
                    </div>

                </div>
            </div>
            <form method="POST" action="{{ route('student.storeTest', ['malop' => $malop]) }}">
                @csrf
                <input type="hidden" name="msbkt" value="{{ $test->msbkt }}">
                <input type="hidden" name="mssv" value="{{ $mssv }}">
                <div class="class-list-2">
                    <div class="class-name-2">
                        <div class="test-name">
                            <p>{{ $test->tenbkt }}</p>
                        </div>
                        <!-- Hiển thị câu trả lời cho từng câu hỏi -->
                        <div class="question-container">
                            <div class="answer-container">
                                <!-- Tạo hàng ngang cho các ô trả lời -->
                                @foreach ($test->cauHoi as $index => $cauHoi)
                                    <div class="answer-box" id="box-{{ $index }}"
                                        onclick="showAnswerBox({{ $index }})">
                                        {{ $index + 1 }}
                                    </div>
                                @endforeach
                            </div>
                            @foreach ($test->cauHoi as $index => $cauHoi)
                                <div class="question-box" id="question-{{ $index }}" style="display: none;">
                                    <p style="font-weight: 700; margin-bottom: 10px">Câu {{ $index + 1 }}
                                        ({{ $cauHoi->diem }} điểm)
                                        :</p>
                                    <p class="answer-instruction">Nhập đáp án để trả lời</p>
                                    <div class="answer-card">
                                        <p>Phiếu trả lời</p>
                                    </div>

                                    <!-- Input để người dùng nhập câu trả lời -->
                                    <input type="text" name="answers[{{ $cauHoi->id }}]"
                                        id="answer-input-{{ $index }}" class="answer-input"
                                        placeholder="Đáp án câu {{ $index + 1 }}: A, B, C, D"
                                        onchange="saveAnswer({{ $index }})">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="button-group">
                    <button class="submit-btn" type="submit">SUBMIT</button>
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

    .class-list {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        align-self: stretch;
    }

    .class-name {
        background: #208CE4;
        padding: 20px;
        color: #FFF;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 20px;
        flex: 1 0 0;
        width: 100%;
    }

    .time {
        display: flex;
        height: 40px;
        flex-direction: column;
        justify-content: center;
        /*Căn giữa theo chiều ngang*/
        align-items: center;
        /*Căn giữa theo chiều dọc*/
        padding: 0;
    }

    .time p {
        color: #FFF;
        font-size: 16px;
        font-weight: 400;
        line-height: normal;
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

    .question-container {
        max-width: 100%;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .question-title {
        color: #000;
        font-size: 16px;
        font-weight: 700;
        align-items: flex-start;
        width: 100%;
        display: block;
        text-align: left;
        margin: 0;
    }

    .answer-instruction {
        width: 100%;
        color: #333;
        font-size: 14px;
        margin: 5px 0 10px;
        align-items: flex-start;
        display: block;
        margin: 0;
        text-align: left;
    }

    .answer-card {
        margin-top: 10px;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        gap: 20px;
    }

    .answer-card p {
        font-weight: 700;
        margin-bottom: 10px;
        text-align: center;
    }

    .answer-container {
        display: flex;
        justify-content: flex-start;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 10px;
    }

    .answer-box {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        width: 40px;
        height: 40px;
        border: 2px solid #007bff;
        font-size: 14px;
        font-weight: 400;
        color: #000;
        cursor: pointer;
        transition: background-color 0.3s;
        background-color: #fff;
        width: calc(20% - 10px);
    }

    .answer-box.selected {
        background-color: #E3F2FD !important;
        /* Thêm một lớp mới để thay đổi màu nền */
        border-color: #007bff !important;
        /* Đổi màu border nếu cần */
    }

    .answer-input {
        width: 100%;
        padding: 20px;
        font-size: 14px;
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 5px;
        text-align: left;
    }

    .answer-input:focus {
        outline: none;
        /* Loại bỏ viền mặc định */
        border-color: #007bff;
        /* Đổi màu viền khi nhấn vào */
        border-width: 2px;
    }

    .answer-options {
        display: flex;
        justify-content: space-between;
        gap: 5px;
    }

    .option-button {
        flex: 1;
        padding: 5px;
        font-size: 14px;
        font-weight: 700;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #fff;
        cursor: pointer;
    }

    .option-button:hover {
        background-color: #e9ecef;
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

    .button-exit {
        flex: 1;
        max-width: 120px;
        height: 40px;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 5px;
        cursor: pointer;
        font-weight: 700;
        font-size: 16px;
        margin: 0 5px;
        background: #D0D0D0;
        color: #000;
    }

    .button-submit {
        flex: 1;
        max-width: 100%;
        height: 40px;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 5px;
        cursor: pointer;
        font-weight: 700;
        font-size: 16px;
        margin: 0 5px;
        background: #208CE4;
        color: #FFF;
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

    .exit-btn {
        background-color: #B9B9B9;
        font-family: "Inter";
        font-size: 16px;
        padding: 10px;
        color: #000;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .exit-btn:hover {
        background-color: #f0f2f5;
    }
</style>

<script>
    // Lưu các câu trả lời
    let answers = {};

    // Hàm hiển thị câu trả lời cho câu hỏi khi người dùng nhấn vào ô trả lời
    function showAnswerBox(questionIndex) {
        // Ẩn tất cả các câu hỏi
        const allQuestions = document.querySelectorAll('.question-box');
        allQuestions.forEach((question) => {
            question.style.display = 'none';
        });

        // Hiển thị câu hỏi tương ứng
        const currentQuestion = document.getElementById(`question-${questionIndex}`);
        currentQuestion.style.display = 'block';

        // Đổi màu cho ô trả lời đã được chọn
        const allAnswerBoxes = document.querySelectorAll('.answer-box');
        allAnswerBoxes.forEach((box) => {
            box.style.backgroundColor = ''; // reset background color
        });

        const currentBox = document.getElementById(`box-${questionIndex}`);
        currentBox.style.backgroundColor = "#E3F2FD"; // màu xanh nhạt khi câu hỏi được chọn
    }

    // Hàm lưu câu trả lời và thay đổi màu ô trả lời nếu đã nhập
    function saveAnswer(questionIndex) {
        const inputField = document.getElementById(`answer-input-${questionIndex}`);
        const box = document.getElementById(`box-${questionIndex}`);

        // Kiểm tra xem câu trả lời đã nhập chưa
        if (inputField.value.trim() !== "") {
            box.classList.add('selected');
            console.log(`Câu ${questionIndex + 1}: Đã trả lời - ${inputField.value}`);
        } else {
            box.classList.remove('selected');
            console.log(`Câu ${questionIndex + 1}: Chưa có câu trả lời`);
        }
    }


    document.addEventListener('DOMContentLoaded', function() {
        // Hiển thị câu hỏi đầu tiên
        showAnswerBox(0);

        const allAnswerInputs = document.querySelectorAll('.answer-input');
        allAnswerInputs.forEach((input, index) => {
            input.addEventListener('input', function() {
                saveAnswer(index); // Kiểm tra và lưu câu trả lời khi người dùng nhập
            });

            // Kiểm tra xem câu trả lời có sẵn không khi trang tải và tô màu ô trả lời nếu đã có giá trị
            const box = document.getElementById(`box-${index}`);
            if (input.value.trim() !== "") {
                box.style.backgroundColor = "#E3F2FD";
                box.classList.add('selected');
            }
        });
    });


    // Lấy thời gian còn lại từ server (ở dạng phút)
    let timeRemaining = parseInt("{{ $test->thoigianlambai }}") * 60; // Chuyển đổi sang giây
    let malop = "{{ $malop }}";

    // Hàm để cập nhật đồng hồ đếm ngược
    function updateCountdown() {
        let minutes = Math.floor(timeRemaining / 60);
        let seconds = timeRemaining % 60;

        document.getElementById("countdown").innerText = `${minutes}:${seconds < 10 ? '0' + seconds : seconds}`;

        // Giảm thời gian còn lại mỗi giây
        timeRemaining--;

        // Kiểm tra nếu hết thời gian
        if (timeRemaining < 0) {
            clearInterval(countdownInterval); // Dừng đồng hồ đếm ngược
            alert("Bạn đã hết thời gian làm bài!"); // Thông báo hết thời gian
            window.location.href = "{{ route('student.class.tests', ['malop' => $malop]) }}";
        }
    }
    // Bắt đầu đồng hồ đếm ngược sau khi tải trang
    let countdownInterval = setInterval(updateCountdown, 1000);
</script>
