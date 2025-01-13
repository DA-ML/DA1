<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Teacher Grading</title>
</head>

<link rel="stylesheet" href="{{ asset('css/global.css') }}">

<div class="grading">
    @include('components.heading')
    <div class="grading-heading">
        <div class="grading-text">
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" fill="none">
                <path
                    d="M14.4571 11.9571C14.8476 11.5666 14.8476 10.9334 14.4571 10.5429C14.0666 10.1524 13.4334 10.1524 13.0429 10.5429L14.4571 11.9571ZM10 15L9.29289 14.2929C8.90237 14.6834 8.90237 15.3166 9.29289 15.7071L10 15ZM13.0429 19.4571C13.4334 19.8476 14.0666 19.8476 14.4571 19.4571C14.8476 19.0666 14.8476 18.4334 14.4571 18.0429L13.0429 19.4571ZM20 16C20.5523 16 21 15.5523 21 15C21 14.4477 20.5523 14 20 14V16ZM27.25 15C27.25 8.23451 21.7655 2.75 15 2.75V4.75C20.6609 4.75 25.25 9.33908 25.25 15H27.25ZM15 2.75C8.23451 2.75 2.75 8.23451 2.75 15H4.75C4.75 9.33908 9.33908 4.75 15 4.75V2.75ZM2.75 15C2.75 21.7655 8.23451 27.25 15 27.25V25.25C9.33908 25.25 4.75 20.6609 4.75 15H2.75ZM15 27.25C21.7655 27.25 27.25 21.7655 27.25 15H25.25C25.25 20.6609 20.6609 25.25 15 25.25V27.25ZM13.0429 10.5429L9.29289 14.2929L10.7071 15.7071L14.4571 11.9571L13.0429 10.5429ZM9.29289 15.7071L13.0429 19.4571L14.4571 18.0429L10.7071 14.2929L9.29289 15.7071ZM10 16H20V14H10V16Z"
                    fill="black" />
            </svg>
            {{ $test->tenbkt }}
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" fill="none">
                <path
                    d="M15.5429 18.0429C15.1524 18.4334 15.1524 19.0666 15.5429 19.4571C15.9334 19.8476 16.5666 19.8476 16.9571 19.4571L15.5429 18.0429ZM20 15L20.7071 15.7071C21.0976 15.3166 21.0976 14.6834 20.7071 14.2929L20 15ZM16.9571 10.5429C16.5666 10.1524 15.9334 10.1524 15.5429 10.5429C15.1524 10.9334 15.1524 11.5666 15.5429 11.9571L16.9571 10.5429ZM10 14C9.44772 14 9 14.4477 9 15C9 15.5523 9.44772 16 10 16V14ZM27.25 15C27.25 8.23451 21.7655 2.75 15 2.75V4.75C20.6609 4.75 25.25 9.33908 25.25 15H27.25ZM15 2.75C8.23451 2.75 2.75 8.23451 2.75 15H4.75C4.75 9.33908 9.33908 4.75 15 4.75V2.75ZM2.75 15C2.75 21.7655 8.23451 27.25 15 27.25V25.25C9.33908 25.25 4.75 20.6609 4.75 15H2.75ZM15 27.25C21.7655 27.25 27.25 21.7655 27.25 15H25.25C25.25 20.6609 20.6609 25.25 15 25.25V27.25ZM16.9571 19.4571L20.7071 15.7071L19.2929 14.2929L15.5429 18.0429L16.9571 19.4571ZM20.7071 14.2929L16.9571 10.5429L15.5429 11.9571L19.2929 15.7071L20.7071 14.2929ZM20 14L10 14V16L20 16V14Z"
                    fill="black" />
            </svg>
        </div>
    </div>
    <div class="body">
        <div class="left">
            @if ($filePath)
                @php
                    $extension = pathinfo($filePath, PATHINFO_EXTENSION);
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
                @endif
            @else
                <p>Không có file để hiển thị.</p>
            @endif
        </div>


        <div class="right">
            <div style="display: flex; justify-content: center; align-self: stretch; align-items: center">
                <h1>KẾT QUẢ LÀM</h1>
            </div>
            <div class="result-text">
                <p><strong>Họ tên sinh viên:</strong></p>
                <p>{{ $student->tensv }}</p>
            </div>
            <div class="result-text">
                <p><strong>Mã số sinh viên:</strong></p>
                <p>{{ $student->mssv }}</p>
            </div>
            <form method="POST"
                action="{{ route('teacher.grading.submit', ['malop' => $class->malop, 'msbkt' => $test->msbkt, 'mssv' => $student->mssv]) }}">
                @csrf
                <div class="grading-form">
                    <table class="grading-table">
                        <thead>
                            <tr>
                                <th>Chuẩn đầu ra</th>
                                <th>Điểm quy định</th>
                                <th>Điểm nhập</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($outcomes as $outcome)
                                <tr>
                                    <td>{{ $outcome->outcome_name }}</td>
                                    <td>({{ $outcome->predefined_point }})</td>
                                    <td>
                                        <input class="input-grade" type="number"
                                            name="points[{{ $outcome->question_id }}]" min="0"
                                            max="{{ $outcome->predefined_point }}" step="0.05" required title="Nhập hoặc chọn điểm từng câu">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="grading-comment">
                        <p>Nhận xét:</p>
                        <textarea name="comment" id="comment" rows="4" maxlength="500" class="comment-box" title="Nhận xét"></textarea>
                    </div>
                    <div class="grading-button">
                        <button type="submit">CHẤM ĐIỂM</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .grading {
        display: flex;
        width: 100%;
        height: 100%;
        flex-direction: column;
        align-items: flex-start;
        overflow: hidden;
    }

    .heading-dashboard p:nth-child(2) {
        color: #208CE4;
        font-weight: 700;
    }

    .grading-heading {
        display: flex;
        height: 60px;
        padding: 0px 20px;
        justify-content: space-between;
        align-items: center;
        flex-shrink: 0;
        align-self: stretch;
        border-bottom: 1px solid rgba(0, 60, 60, 0.20);
    }

    .grading-text {
        display: flex;
        align-items: center;
        gap: 100px;
        color: #000;
        font-family: Inter;
        font-size: 20px;
        font-style: normal;
        font-weight: 400;
        line-height: normal;
    }

    .body {
        display: flex;
        align-items: flex-start;
        flex: 1 0 0;
        align-self: stretch;
        overflow-y: auto;
    }

    .left {
        display: flex;
        width: 60%;
        padding: 10px;
        flex-direction: column;
        justify-content: center;
        align-items: flex-start;
        gap: 10px;
        align-self: stretch;
    }

    .right {
        display: flex;
        padding: 20px;
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
        flex: 1 0 0;
        align-self: stretch;
    }

    .right h1 {
        color: #208ce4;
        font-family: Inter;
        font-size: 24px;
        font-style: normal;
        font-weight: 700;
        line-height: normal;
        align-items: center;
    }

    .result-text {
        display: flex;
        justify-content: space-between;
        align-items: center;
        align-self: stretch;
        width: 100%;
        margin-left: 10px;
        margin-top: 10px;
    }

    .result-text p {
        color: #000;
        font-family: Inter;
        font-size: 18px;
        font-style: normal;
        font-weight: 400;
        line-height: normal;
    }

    .grading-point p {
        font-family: "Inter";
        font-size: 18;
        font-weight: 700;
    }

    .grading-comment p {
        font-family: "Inter";
        font-size: 18;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .grading-comment {
        width: 100%;
        gap: 10px;
    }

    .grading-form {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        width: 100%;
        gap: 20px;
        margin-bottom: 10px;
    }

    .comment-box {
        width: 100%;
        height: 200px;
        resize: none;
    }

    .grading-button {
        width: 100%;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        align-self: stretch;
    }

    .grading-button button {
        background-color: #208ce4;
        color: #FFF;
        padding: 10px 20px;
        font-weight: 700;
    }

    .grading-table {
        width: 100%;
    }

    .grading-table table {
        width: 100%;
        border-collapse: collapse;
        font-family: 'Inter', sans-serif;
    }

    .grading-table th {
        font-size: 16px;
        font-weight: bold;
        text-align: left;
        background-color: #208CE4;
        padding: 8px;
        color: white;
    }

    .grading-table td {
        font-size: 16px;
        font-weight: normal;
        text-align: left;
        padding: 8px;
    }

    .input-grade {
        height: 30px;
        border-radius: 5px;
        border: 1px solid rgba(0, 60, 60, 0.2);
        font-family: "Inter";
        font-size: 16px;
        padding: 10px;
    }
</style>

<script>
    // Kiểm tra khi người dùng nhập điểm
    document.querySelectorAll('input[type="number"]').forEach(function(input) {
        input.addEventListener('input', function() {
            let maxPoint = parseFloat(input.getAttribute('data-max'));
            let enteredPoint = parseFloat(input.value);

            if (enteredPoint > maxPoint) {
                input.setCustomValidity('Điểm không được lớn hơn điểm quy định!');
            } else {
                input.setCustomValidity('');
            }
        });
    });
</script>
