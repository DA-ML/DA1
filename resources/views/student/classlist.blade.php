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
                @include('components.filter')
                @include('components.search_button')
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
                                <a href="{{ route('class.details', $class->malop) }}" class="btn btn-primary"
                                    style="color: #208ce4; text-decoration: none">
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
</style>
</html>