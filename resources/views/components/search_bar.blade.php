<input type="text" class="search-bar" id="search-bar" placeholder="Tìm kiếm">

<style>
    .search-bar {
        display: flex;
        width: 100%;
        padding: 15px;
        align-items: center;
        border-radius: 10px;
        border: 1px solid rgba(0, 60, 60, 0.20);
        background: #FFF;
        color: rgba(0, 0, 0, 0.50);
        font-family: Inter;
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: normal;
        cursor: pointer;
    }

    .search-bar:not(:focus):hover {
        cursor: text;
        border-color: rgba(0, 60, 60, 1.0);
    }

    .search-bar:focus {
        cursor: text;
        outline: none;
        box-shadow: 0 0 0 1px rgba(32, 140, 228, 1);
    }
</style>

<script>
    document.getElementById('search-bar').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase(); // Lấy từ khóa tìm kiếm và chuyển thành chữ thường

        // Lấy tất cả các bảng trong trang
        const tables = document.querySelectorAll('table'); // Lấy tất cả các bảng trên trang

        tables.forEach(function(table) {
            const rows = table.querySelectorAll('tbody tr'); // Lấy tất cả các hàng trong mỗi bảng

            rows.forEach(function(row) {
                const cells = row.querySelectorAll('td'); // Các cột trong mỗi hàng
                let rowText = ''; // Biến để lưu trữ văn bản của hàng

                // Lấy toàn bộ văn bản trong các cột của hàng
                cells.forEach(function(cell) {
                    rowText += cell.textContent.toLowerCase() +
                        ' '; // Thêm vào toàn bộ văn bản của hàng
                });

                // Kiểm tra xem từ khóa có trong văn bản của hàng không
                if (rowText.includes(searchTerm)) {
                    row.style.display = ''; // Hiển thị hàng nếu tìm thấy từ khóa
                } else {
                    row.style.display = 'none'; // Ẩn hàng nếu không tìm thấy từ khóa
                }
            });
        });
    });
</script>
