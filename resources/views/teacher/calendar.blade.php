<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Teacher Calendar</title>
</head>

<div class="class-list">
    @include('components.heading')
    <div class="body">
        {{-- Calendar --}}
        <div class="calendar-container">
            <div id="calendar"></div>
        </div>
    </div>
</div>

<style>
    .class-list {
        display: flex;
        width: 100%;
        height: 100%;
        flex-direction: column;
        align-items: flex-start;
        overflow: hidden;
    }

    .heading-dashboard p:nth-child(3) {
        color: #208ce4;
        font-weight: 700;
    }

    .body {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        flex-shrink: 0;
        width: 100%;
        background: #FFF;
        align-items: center;
        align-self: stretch;
        justify-content: center;
        overflow: hidden;
    }

    .calendar-container {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        padding: 20px;
    }

    .calendar-header {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 10px;
        font-family: "Inter";
    }

    .calendar-week-header {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        text-align: center;
        font-size: 18px;
        font-weight: 700;
        font-family: "Inter";
        background-color: #208ce4;
        color: #FFF;
        padding: 10px 0;
        border-bottom: 1px solid #ddd;
        gap: 10px;
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 5px;
        padding: 10px 0;
    }

    .calendar-cell {
        width: 100%;
        aspect-ratio: 1;
        text-align: center;
        line-height: 1rem;
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 5px;
        box-sizing: border-box;
        font-size: 18px;
        font-weight: 400;
        font-family: "Inter";
        align-items: center;
        align-self: stretch;
        display: flex;
        justify-content: center;
    }

    .calendar-cell:hover {
        background-color: #f0f2f5;
        color: #000;
    }

    .calendar-cell.exam-day {
        background-color: #E3F2FD;
        color: #208ce4;
        font-weight: bold;
    }

    .calendar-cell.exam-day:hover {
        background-color: #208ce4;
        color: #FFF;
    }

    .calendar-cell.empty {
        background-color: transparent;
        border: none;
    }

    .calendar-week-day {
        padding: 5px 0;
    }

    .calendar-week-day:first-child {
        margin-left: 15px;
    }

    .calendar-week-day:last-child {
        margin-right: 15px;
    }

    .tooltip {
        position: absolute;
        background-color: #FFF;
        color: #000000;
        padding: 5px 10px;
        border-radius: 5px;
        z-index: 1000;
        pointer-events: none;
        border: 1px solid rgba(0, 60, 60, 0.50);
        font-family: "Inter";
        font-size: 16px;
    }
</style>
<script>
    const exams = @json($exams);
    const weekDays = ["Chủ nhật", "Thứ Hai", "Thứ Ba", "Thứ Tư", "Thứ Năm", "Thứ Sáu", "Thứ Bảy"];
    const monthNames = [
        "Tháng 1", "Tháng 2", "Tháng 3", "Tháng 4", "Tháng 5", "Tháng 6",
        "Tháng 7", "Tháng 8", "Tháng 9", "Tháng 10", "Tháng 11", "Tháng 12"
    ];

    function generateCalendar(year, month) {
        calendar.innerHTML = "";

        // Tiêu đề lịch: Tháng + Năm
        const header = document.createElement("div");
        header.className = "calendar-header";
        header.textContent = `${monthNames[month]}, ${year}`;
        calendar.appendChild(header);

        // Hàng hiển thị các thứ trong tuần
        const weekHeader = document.createElement("div");
        weekHeader.className = "calendar-week-header";

        weekDays.forEach(day => {
            const dayCell = document.createElement("div");
            dayCell.className = "calendar-week-day";
            dayCell.textContent = day;
            weekHeader.appendChild(dayCell);
        });

        calendar.appendChild(weekHeader);

        // Lưới ngày trong tháng
        const daysGrid = document.createElement("div");
        daysGrid.className = "calendar-grid";

        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();

        // Các ô trống trước ngày đầu tiên của tháng
        for (let i = 0; i < firstDay; i++) {
            const emptyCell = document.createElement("div");
            emptyCell.className = "calendar-cell empty";
            daysGrid.appendChild(emptyCell);
        }

        // Các ô ngày trong tháng
        for (let day = 1; day <= daysInMonth; day++) {
            const cell = document.createElement("div");
            cell.className = "calendar-cell";
            cell.textContent = day;

            // Định dạng ngày để so sánh với `exams`
            const dateStr = `${year}-${(month + 1).toString().padStart(2, "0")}-${day.toString().padStart(2, "0")}`;

            const examList = exams[dateStr];
            if (examList) {
                cell.classList.add("exam-day");
                cell.setAttribute("data-exam-names", examList.join(", "));
            }

            // Thêm sự kiện hover
            cell.addEventListener("mouseover", function () {
                const examNames = cell.getAttribute("data-exam-names");
                if (examNames) {
                    const tooltip = document.createElement("div");
                    tooltip.className = "tooltip";
                    tooltip.textContent = `Bài kiểm tra: ${examNames}`;
                    document.body.appendChild(tooltip);

                    const rect = cell.getBoundingClientRect();
                    tooltip.style.left = `${rect.left + window.scrollX}px`;
                    tooltip.style.top = `${rect.top + window.scrollY - tooltip.offsetHeight}px`;
                }
            });

            cell.addEventListener("mouseout", function () {
                const tooltip = document.querySelector(".tooltip");
                if (tooltip) tooltip.remove();
            });

            daysGrid.appendChild(cell);
        }

        calendar.appendChild(daysGrid);
    }

    const calendar = document.getElementById("calendar");
    const today = new Date();
    generateCalendar(today.getFullYear(), today.getMonth());
</script>

</html>
