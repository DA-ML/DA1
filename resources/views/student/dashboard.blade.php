<div class="dashboard">
    @include('components.heading')
    <div class="body">
        <div class="right">
            <div class="class_list">
                <p>Lớp học:</p>
                <div class="container"></div>
            </div>
            <div class="class_list">
                <p>Bài giảng:</p>
                <div class="container"></div>
            </div>
            <div class="class_list">
                <p>Bài tập:</p>
                <div class="container"></div>
            </div>
            <div class="class_list">
                <p>Thống kê:</p>
                <div class="container"></div>
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
    }

    .right {
        display: flex;
        padding: 20px;
        flex-direction: column;
        align-items: flex-start;
        gap: 20px;
        flex: 1 0 0;
        align-self: stretch;
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
        height: 54px;
        padding: 10px;
        align-items: flex-start;
        gap: 10px;
        align-self: stretch;
        border-radius: 10px;
        background: #FFF;
    }
</style>
