<link rel="stylesheet" href="{{ asset('css/global.css') }}">

<div class="question-info">
    <div class="text">
        Câu
    </div>
    <div class="row-1">
        Đáp án
        <input type="text" style="width: 70px">
    </div>
    <div class="row-1">
        CDR
        <select id="cdr-dropdown" name="cdr"
            style="width: 70px; height: 40px; border-radius: 5px; border: 1px solid rgba(0, 60, 60, 0.2); font-family: Inter;">
            <option value="">Chọn CDR</option>
        </select>
    </div>
    <div class="row-1">
        Điểm
        <input type="text" style="width: 70px">
    </div>
</div>

<style>
    .question-info {
        display: flex;
        width: 200px;
        padding: 20px;
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
        border-radius: 10px;
        border: 1px solid rgba(44, 148, 231, 0.50);
        background: #E3F2FD;
    }

    .text {
        color: #2C94E7;
        font-family: Inter;
        font-size: 12px;
        font-style: normal;
        font-weight: 400;
        line-height: 130%;
        /* 20.8px */
    }

    .row-1 {
        display: flex;
        justify-content: space-between;
        align-items: center;
        align-self: stretch;
        white-space: nowrap;
        color: #000;
        font-family: Inter;
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: 130%;
        /* 20.8px */
    }

    .input {
        width: 100px;
        height: 35px;
        border-radius: 10px;
        background: #FFF;
    }
</style>
