<link rel="stylesheet" href="{{ asset('css/global.css') }}">
<div class="test-form">
    @include('components.heading')
    <div class="body">
        <div class="right">
            <div class="class-test">
                <div class="test-btn">
                    <div class="test-img">
                        {{-- File hiển thị ở đây --}}
                        <div class="custom-file-upload">
                            <label for="file-input" class="custom-button">Chọn tệp</label>
                            <input type="file" id="file-input" accept=".jpg, .jpeg, .png, .pdf, .docx, .doc"
                                onchange="handleFileUpload(event)">
                        </div>

                    </div>
                    <!-- Container hiển thị file -->
                    <div id="file-preview"></div>
                </div>
                <div class="test-setting">
                    @include('components.test_progress')
                    <div class="number-question">
                        <div class="number">
                            <div class="tpdg">
                                TPDG
                                <select id="tpdg-dropdown" name="tpdg"
                                    style="width: 70px; height: 40px; border-radius: 5px; border: 1px solid rgba(0, 60, 60, 0.2); font-family: Inter; padding: 10px">
                                    <option value="A1">A1</option>
                                    <option value="A2">A2</option>
                                    <option value="A3">A3</option>
                                    <option value="A4">A4</option>
                                </select>
                            </div>
                            <button class="tertiary" id="next-button">
                                Tiếp theo
                            </button>
                        </div>
                    </div>
                    <div>
                        <div style="margin-top:20px; margin-left: 20px;">
                            <h1 style="font-family: Inter; font-size: 16px; font-weight: 400;">Lưu ý: Tổng điểm của
                                các thành phần
                                đánh giá phải là 10</h1>
                            <div id="cdr-inputs-container"
                                style="display: none; margin-top: 20px; width: 100%; justify-content: space-between; align-self: stretch;">
                                <!-- Các input phần trăm sẽ được thêm vào đây -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .test-form {
        display: flex;
        width: 100%;
        height: 100%;
        flex-direction: column;
        align-items: flex-start;
        background: #FFF;
    }

    .body {
        display: flex;
        align-items: flex-start;
        flex: 1 0 0;
        align-self: stretch;
        background: #F0F2F5;
        overflow: hidden;
    }

    .right {
        display: flex;
        padding: 20px;
        flex-direction: column;
        align-items: flex-start;
        flex: 1 0 0;
        align-self: stretch;
    }

    .class-info {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 20px;
        align-self: stretch;
    }

    .class-name {
        display: flex;
        padding: 20px;
        flex-direction: column;
        align-items: flex-start;
        gap: 20px;
        align-self: stretch;
        background: #FFF;
        color: #000;
        font-family: Inter;
        font-size: 20px;
        font-style: normal;
        font-weight: 700;
        line-height: normal;
    }

    .class-id {
        display: flex;
        align-items: center;
        gap: 20px;
        color: #000;
        font-family: Inter;
        font-size: 20px;
        font-style: normal;
        font-weight: 400;
        line-height: normal;
    }

    .class-test {
        display: flex;
        align-items: flex-start;
        flex: 1 0 0;
        align-self: stretch;
        background: #FFF;
    }

    .test-btn {
        display: flex;
        width: 50%;
        height: 100%;
        padding: 20px;
        flex-direction: column;
        align-items: flex-start;
        gap: 20px;
        align-self: stretch;
    }

    .test-setting {
        display: flex;
        padding: 20px;
        flex-direction: column;
        align-items: flex-start;
        flex: 1 0 0;
        align-self: stretch;
        background: #FFF;
        width: 50%;
    }

    .test-img {
        display: flex;
        flex-direction: column;
        /* Sắp xếp các phần tử theo chiều dọc */
        align-items: flex-start;
        /* Căn trái các phần tử con */
        gap: 20px;
    }

    .number-question {
        display: flex;
        padding: 20px;
        justify-content: space-between;
        align-items: center;
        align-self: stretch;
        background: #004b9a;
        width: 100%;
    }

    .number-question a {
        text-decoration: none;
    }

    .number {
        display: flex;
        align-items: center;
        justify-content: space-between;
        color: #FFF;
        font-family: Inter;
        font-size: 20px;
        font-style: normal;
        font-weight: 700;
        line-height: 130%;
        /* 26px */
        white-space: nowrap;
        width: 100%;
        height: 40px;
    }

    .tpdg {
        width: 100%;
        display: flex;
        align-items: center;
        gap: 30px;
        color: #FFF;
        font-family: Inter;
        font-size: 20px;
        font-style: normal;
        font-weight: 700;
        line-height: 130%;
        /* 26px */
        height: 40px;
    }

    .question-information {
        display: flex;
        padding: 10px;
        align-items: center;
        flex-direction: row;
        /* Thay đổi từ column sang row */
        align-items: flex-start;
        gap: 20px;
        flex-wrap: wrap;
        /* Cho phép các phần tử xuống dòng */
        max-height: 450px;
        /* Thiết lập chiều cao tối đa cho khu vực cuộn */
        overflow-y: auto;
        /* Kích hoạt cuộn dọc */
        width: 100%;
        /* Đảm bảo chiều rộng bao toàn bộ */
    }

    .question-info {
        padding: 20px;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 20px;
        border-radius: 10px;
        border: 1px solid rgba(44, 148, 231, 0.50);
        background: #E3F2FD;
        box-sizing: border-box;
    }

    select,
    option {
        color: black;
        /* Đặt màu chữ là đen */
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
        gap: 20px;
    }

    .text {
        color: #2C94E7;
        font-family: Inter;
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: 130%;
        /* 20.8px */
    }

    #file-preview {
        overflow-y: auto;
        max-height: 550px;
    }

    input[type="file"] {
        display: none;
        /* Ẩn input thực */
    }

    .custom-file-upload {
        display: inline-block;
        position: relative;
    }

    .custom-button {
        display: inline-block;
        background-color: #208ce4;
        color: #fff;
        padding: 10px 20px;
        /* Khoảng cách */
        font-family: Inter;
        font-size: 16px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .custom-button:hover {
        background-color: #004b9a;
        /* Màu khi hover */
    }

    .cdr-label {
        font-family: "Inter";
        font-size: 16px;
        margin-right: 20px;
    }

    .cdr-input {
        border-radius: 5px;
        border: 1px solid rgba(0, 60, 60, 0.2);
        font-family: "Inter";
        font-size: 16px;
        width: 200px;
    }

    .cdr-div {
        display: flex;
        /* Bật flexbox */
        justify-content: space-between;
        /* Căn chỉnh các phần tử con với khoảng cách đều */
        align-items: center;
        /* Căn giữa theo trục dọc */
        width: 300px;
        /* Đảm bảo div chiếm toàn bộ chiều rộng của phần tử cha */
    }


    #cdrInputsContainer {
        width: 100%;
        justify-content: space-between;
    }
</style>
<script>
    // Hàm để gọi API và hiển thị input CDR
    function updateCdrInputs(tpdg) {
        const cdrInputsContainer = document.getElementById('cdr-inputs-container');
        cdrInputsContainer.innerHTML = ''; // Xóa các input cũ trước khi thêm mới

        if (!tpdg) {
            alert('Vui lòng chọn một TPDG!');
            return;
        }

        // Gọi API để lấy danh sách CDR
        fetch(`/api/get-cdr?tpdg=${tpdg}`)
            .then((response) => response.json())
            .then((data) => {
                console.log('Dữ liệu CDR nhận được từ API:', data);
                if (data.cdrs && data.cdrs.length > 0) {
                    // Hiển thị input cho từng CDR
                    data.cdrs.forEach((cdr) => {
                        const div = document.createElement('div');
                        div.style.marginBottom = '10px';
                        div.classList.add('cdr-div');

                        const label = document.createElement('label');
                        label.textContent = cdr.chuan;
                        label.classList.add('cdr-label');
                        div.appendChild(label);

                        const input = document.createElement('input');
                        input.type = 'number';
                        input.name = `cdr_${cdr.id}`;
                        input.placeholder = 'Điểm';
                        input.style.marginLeft = '10px';
                        input.style.padding = '5px';
                        input.style.width = '150px';
                        input.setAttribute('min', '0');
                        input.setAttribute('max', '10');
                        input.classList.add('cdr-input');

                        div.appendChild(input);
                        cdrInputsContainer.appendChild(div);
                    });

                    // Hiển thị phần input
                    cdrInputsContainer.style.display = 'block';
                } else {
                    alert('Không tìm thấy chuẩn đầu ra.');
                }
            })
            .catch((error) => {
                console.error('Lỗi khi gọi API:', error);
            });
    }

    // Lắng nghe sự thay đổi của TPDG và cập nhật các dropdown CDR
    document.getElementById('tpdg-dropdown').addEventListener('change', function() {
        const tpdg = this.value;
        updateCdrInputs(tpdg); // Gọi lại hàm cập nhật khi người dùng thay đổi TPDG
    });

    // Khi trang được tải, mặc định chọn A1
    document.addEventListener('DOMContentLoaded', function() {
        updateCdrInputs('A1'); // Gọi hàm để hiển thị input cho A1 ngay khi trang tải
    });

    // Lắng nghe sự kiện "Tiếp theo" để kiểm tra tổng điểm
    document.getElementById('next-button').addEventListener('click', function() {
        const inputs = document.querySelectorAll('#cdr-inputs-container input');
        let totalPercent = 0;

        inputs.forEach(input => {
            totalPercent += parseFloat(input.value) || 0; // Tính tổng phần trăm
        });

        if (totalPercent !== 10) {
            alert('Tổng điểm phải là 10. Hiện tại là ' + totalPercent + ' điểm');
        } else {
            alert('Tổng điểm hợp lệ. Tiến hành tiếp theo.');
        }
    });

    // Hàm xử lý khi người dùng chọn file
    function handleFileUpload(event) {
        const file = event.target.files[0];
        const previewContainer = document.getElementById('file-preview');

        if (file) {
            // Clear nội dung trước đó
            previewContainer.innerHTML = '';

            // Kiểm tra loại file và hiển thị phù hợp
            const fileType = file.type;

            if (fileType.includes('image')) {
                // Nếu là hình ảnh, hiển thị hình ảnh
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file); // Tạo URL cho file
                img.alt = file.name;
                img.style.maxWidth = '100%'; // Đảm bảo hình ảnh không vượt quá container
                img.style.marginBottom = '10px';
                previewContainer.appendChild(img);
            } else if (fileType === 'application/pdf') {
                const iframe = document.createElement('iframe');
                iframe.src = URL.createObjectURL(file);
                iframe.style.width = '700px';
                iframe.style.height = '700px'; // Tăng chiều cao để PDF rõ hơn
                iframe.style.border = 'none';
                iframe.style.marginTop = '20px';
                previewContainer.appendChild(iframe);

            } else if (
                fileType === 'application/msword' ||
                fileType === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
            ) {
                const fileName = document.createElement('p');
                fileName.textContent = `Đã chọn file Word: ${file.name}`;
                fileName.style.color = '#333';
                fileName.style.fontSize = '16px';
                previewContainer.appendChild(fileName);


            } else {
                // Nếu không phải hình ảnh, PDF, hay Word, hiển thị thông báo lỗi
                const message = document.createElement('p');
                message.textContent = 'Vui lòng chọn file hình ảnh, PDF hoặc Word.';
                message.style.color = 'red';
                previewContainer.appendChild(message);
            }
        }

    }
</script>
