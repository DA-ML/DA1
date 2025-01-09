<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Teacher Add Test</title>
</head>

<link rel="stylesheet" href="{{ asset('css/global.css') }}">

<div class="type">
    @include('components.heading')
    <div class="body">
        <div class="content">
            <div class="test-type">
                <!-- Multiple choice -->
                <div class="combine" data-type="multiple-choice">
                    <div class="combine-pic">
                        <div class="picc">
                            <img src="{{ asset('images/multiple-choice.png') }}" alt="multiple-choice">
                        </div>
                        <div class="combine-text">
                            <h1>Trắc nghiệm</h1>
                            <p>Chỉ hỗ trợ file PDF, PNG</p>
                        </div>
                    </div>
                    <button class="decor"
                        onclick="window.location.href='{{ url('teacher/add/test/form/' . $class->malop) }}'">
                        Trắc nghiệm</button>
                </div>
            </div>
            <div class="test-type">
                <!-- Essay -->
                <div class="combine" data-type="essay">
                    <div class="combine-pic">
                        <div class="picc">
                            <img src="{{ asset('images/writing.png') }}" alt="essay">
                        </div>
                        <div class="combine-text">
                            <h1>Tự luận</h1>
                            <p>Hỗ trợ chấm bài không cần tải về</p>
                        </div>
                    </div>
                    <button class="decor"
                        onclick="window.location.href='{{ url('teacher/add/test/essay/' . $class->malop) }}'">
                        Tự luận</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .type {
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

    .body {
        display: flex;
        padding: 20px 0px;
        justify-content: center;
        align-items: center;
        flex: 1 0 0;
        align-self: stretch;
        background: #F0F2F5;
    }

    .content {
        display: flex;
        width: 60%;
        padding: 20px 20px 20px 20px;
        justify-content: space-between;
        align-items: center;
        align-self: stretch;
        border-radius: 10px;
        justify-content: space-between;
        gap: 20px;
    }

    .test-type {
        display: flex;
        width: 50%;
        padding: 10px;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        gap: 10px;
        flex-shrink: 0;
        align-self: stretch;
        border-radius: 10px;
        border: 1px solid rgba(0, 60, 60, 0.20);
        background-color: #FFF;
    }

    .combine {
        display: flex;
        width: 90%;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        gap: 51px;
    }

    .combine-pic {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        gap: 46px;
        align-self: stretch;
    }

    .combine-text {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 20px;
        align-self: stretch;
    }

    .combine-text h1 {
        color: #000;
        text-align: center;
        font-family: Inter;
        font-size: 20px;
        font-style: normal;
        font-weight: 700;
        line-height: normal;
    }

    .combine-text p {
        color: #000;
        font-family: Inter;
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: normal;
    }

    .picc {
        width: 200px;
        height: 200px;
    }

    .picc img {
        width: 100%;
        height: 100%;
    }

    .decor {
        display: flex;
        width: 200px;
        padding: 15px;
        justify-content: center;
        align-items: center;
        gap: 20px;
        border-radius: 10px;
        background: #208CE4;
        color: #FFF;
        font-family: Inter;
        font-size: 16px;
        font-weight: 700;
        border: none;
    }

    .decor:hover {
        background-color: #004b9a;
    }
</style>
