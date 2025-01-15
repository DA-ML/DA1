<html lang="vi">
<!DOCTYPE html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Student Class List</title>
</head>

<div class="class-list">
    @include('components.heading')
    <div class="body">
        <div class="class-btn">
            @include('components.search_bar')
            <div class="filter-search">
                <select name="sortOrder" class="form-select" id="sortOrder">
                    <option value="az">A-Z</option>
                    <option value="za">Z-A</option>
                </select>
            </div>
        </div>
        <div class="class-table">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Mã lớp</th>
                        <th>Tên lớp</th>
                        <th>Số học sinh</th>
                        <th>Số bài giảng</th>
                        <th>Số bài kiểm tra</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($classes as $class)
                        <tr>
                            <td>{{ $class->malop }}</td>
                            <td>{{ $class->tenlop }}</td>
                            <td>{{ $class->so_hoc_sinh }}</td>
                            <td>{{ $class->so_bai_giang }}</td>
                            <td>{{ $class->so_bai_kiem_tra }}</td>
                            <td>
                                <a href="{{ route('student.class.details', $class->malop) }}" class="btn-view">
                                    View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Bạn hiện tại không có lớp học nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
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
    }

    .heading-dashboard p:nth-child(2) {
        color: #208ce4;
        font-weight: 700;
    }

    .body {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        flex-shrink: 0;
        width: 100%;
        background: #F0F2F5;
    }

    .class-btn {
        display: flex;
        width: 100%;
        padding: 20px 30px;
        justify-content: space-between;
        align-items: flex-start;
        align-self: stretch;
        background: #FFF;
        gap: 20px;
    }

    .filter-search {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .class-table {
        display: flex;
        padding: 0px 30px;
        justify-content: center;
        align-items: flex-start;
        gap: 10px;
        flex: 1 0 0;
        align-self: stretch;
        background: #FFF;
    }

    /* Table */
    .class-table table {
        width: 100%;
        border-collapse: collapse;
        font-family: 'Inter', sans-serif;
    }

    .class-table th {
        font-size: 16px;
        font-weight: bold;
        padding: 8px;
        text-align: left;
        background-color: #208CE4;
        color: #FFF;
    }

    .class-table td {
        font-size: 16px;
        font-weight: normal;
        padding: 8px;
        text-align: left;
    }

    .class-table td a {
        text-decoration: none;
        color: #208CE4;
    }

    .class-table td a:hover {
        color: #004b9a;
    }

    .form-select {
        padding: 10px;
        border-radius: 5px;
        height: 49px;
        display: flex;
        width: 120px;
        justify-content: space-between;
        align-items: center;
        font-family: Inter;
        font-size: 14px;
        font-style: normal;
        font-weight: 400;
        line-height: normal;
        cursor: pointer;
    }

    .form-select:hover,
    .form-select:focus {
        border-color: #208ce4;
        color: inherit;
        outline: none;
    }
</style>
<script>
    document.getElementById('sortOrder').addEventListener('change', function () {
        const sortOrder = this.value; // Lấy giá trị sắp xếp (az hoặc za)
        const table = document.querySelector('.class-table table'); // Lấy bảng
        const rows = Array.from(table.querySelectorAll('tbody tr')); // Lấy các hàng trong tbody

        // Thực hiện sắp xếp
        rows.sort((rowA, rowB) => {
            const cellA = rowA.querySelector('td:nth-child(1)').textContent.trim(); // Cột "Mã lớp"
            const cellB = rowB.querySelector('td:nth-child(1)').textContent.trim();

            if (sortOrder === 'az') {
                return cellA.localeCompare(cellB); // Sắp xếp tăng dần
            } else {
                return cellB.localeCompare(cellA); // Sắp xếp giảm dần
            }
        });

        // Cập nhật thứ tự hàng trong bảng
        const tbody = table.querySelector('tbody');
        rows.forEach(row => tbody.appendChild(row)); // Thêm lại từng hàng vào tbody
    });
</script>
</html>
