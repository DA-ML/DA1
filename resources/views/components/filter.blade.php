<?php
$options = $options ?? ['A-Z', 'Z-A'];
$dropdown_id = $dropdown_id ?? 'default-dropdown';
$name = $name ?? 'dropdown';
$selected_text = $selected_text ?? 'Lọc theo';
?>

<div class="dropdown" data-dropdown-id="<?= htmlspecialchars($dropdown_id) ?>">
    <div class="dropdown-inner">
        <input type="hidden" name="<?= htmlspecialchars($name) ?>" value=""
            id="<?= htmlspecialchars($dropdown_id) ?>">
        <span class="selected-text"><?= htmlspecialchars($selected_text) ?></span>
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="25" viewBox="0 0 24 25" fill="none">
            <path d="M19 9.5L12 16.5L5 9.5" stroke="black" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" />
        </svg>
        <div class="dropdown-option">
            <?php foreach ($options as $option): ?>
            <div class="option" data-value="<?= htmlspecialchars($option, ENT_QUOTES, 'UTF-8') ?>">
                <p><?= htmlspecialchars($option, ENT_QUOTES, 'UTF-8') ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<style>
    .dropdown {
        display: inline-flex;
        align-items: flex-start;
        position: relative;
        width: 150px;
    }

    .dropdown-inner {
        display: flex;
        width: 100%;
        padding: 12px;
        justify-content: space-between;
        gap: 10px;
        align-items: center;
        border-radius: 10px;
        border: 1px solid rgba(0, 60, 60, 0.20);
        color: #000;
        font-family: Inter;
        font-size: 14px;
        font-style: normal;
        font-weight: 400;
        line-height: 24px;
        cursor: pointer;
        /* 171.429% */
    }

    .dropdown-option {
        display: none;
        width: 100%;
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
        border-radius: 5px;
        border: 1px solid #003C3C;
        background: var(--White, #FFF);
        position: absolute;
        /* Giữ absolute để dropdown nằm đúng vị trí */
        top: 100%;
        left: 0;
        /* Đảm bảo căn giữa từ bên trái */
        z-index: 2000;
    }

    .option {
        display: flex;
        padding: 6px 8px 6px 32px;
        justify-content: center;
        align-items: center;
        gap: 10px;
        align-self: stretch;
    }

    .option p {
        flex: 1 0 0;
        align-self: stretch;
        color: #000;
        font-family: Inter;
        font-size: 14px;
        font-style: normal;
        font-weight: 400;
        line-height: 20px;
        /* 142.857% */
        letter-spacing: 0.42px;
    }

    .dropdown-option.show {
        display: flex;
        /* Show when toggled */
    }

    .option:hover {
        background-color: #6DCFFB;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Biến trạng thái để lưu dropdown đang mở
        let activeDropdown = null;

        // Đóng tất cả các dropdown
        function closeAllDropdowns() {
            if (activeDropdown) {
                const dropdownOptions = activeDropdown.querySelector('.dropdown-option');
                if (dropdownOptions) {
                    dropdownOptions.classList.remove('show');
                }
                activeDropdown = null;
            }
        }

        // Hàm xử lý khi click vào một dropdown
        function handleDropdownClick(dropdown) {
            const dropdownOptions = dropdown.querySelector('.dropdown-option');
            if (!dropdownOptions) return;

            // Nếu dropdown này đang mở, đóng nó
            if (activeDropdown === dropdown) {
                dropdownOptions.classList.remove('show');
                activeDropdown = null;
            } else {
                // Đóng các dropdown khác trước khi mở dropdown này
                closeAllDropdowns();
                dropdownOptions.classList.add('show');
                activeDropdown = dropdown;
            }
        }

        // Lắng nghe sự kiện click trên mỗi dropdown
        document.querySelectorAll('.dropdown').forEach(dropdown => {
            const dropdownInner = dropdown.querySelector('.dropdown-inner');
            dropdownInner.addEventListener('click', function(event) {
                event.stopPropagation(); // Ngăn không cho sự kiện lan ra ngoài
                handleDropdownClick(dropdown);
            });
        });

        // Đóng tất cả dropdown khi click ra ngoài
        document.addEventListener('click', function() {
            closeAllDropdowns();
        });
    });
</script>
