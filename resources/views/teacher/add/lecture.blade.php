<link rel="stylesheet" href="{{ asset('css/global.css') }}">

<div class="lecture-type">
    @include('components.heading')
    <div class="body">
        @include('components.sidebar')
        <div class="right">
            <div class="class-info">
                <div class="class-name">
                    Lớp học: {{ $class->tenlop }}
                    <div class="class-id">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none">
                            <path
                                d="M3 13H21V11H3V13ZM12 20C7.58172 20 4 16.4183 4 12H2C2 17.5228 6.47715 22 12 22V20ZM4 12C4 7.58172 7.58172 4 12 4V2C6.47715 2 2 6.47715 2 12H4ZM12 4C16.4183 4 20 7.58172 20 12H22C22 6.47715 17.5228 2 12 2V4ZM20 12C20 16.4183 16.4183 20 12 20V22C17.5228 22 22 17.5228 22 12H20ZM12 20C11.7731 20 11.4816 19.9007 11.1324 19.5683C10.778 19.2311 10.4134 18.6958 10.0854 17.9578C9.43054 16.4844 9 14.3789 9 12H7C7 14.5917 7.46489 16.9861 8.25776 18.7701C8.65364 19.6608 9.15092 20.4435 9.75363 21.0171C10.3615 21.5956 11.1223 22 12 22V20ZM9 12C9 9.62114 9.43054 7.51558 10.0854 6.04218C10.4134 5.30422 10.778 4.76892 11.1324 4.43166C11.4816 4.0993 11.7731 4 12 4V2C11.1223 2 10.3615 2.40438 9.75363 2.98287C9.15092 3.55645 8.65364 4.33918 8.25776 5.2299C7.46489 7.01386 7 9.40829 7 12H9ZM12 4C12.2269 4 12.5184 4.0993 12.8676 4.43166C13.222 4.76892 13.5866 5.30422 13.9146 6.04218C14.5695 7.51558 15 9.62114 15 12H17C17 9.40829 16.5351 7.01386 15.7422 5.2299C15.3464 4.33918 14.8491 3.55645 14.2464 2.98287C13.6385 2.40438 12.8777 2 12 2V4ZM15 12C15 14.3789 14.5695 16.4844 13.9146 17.9578C13.5866 18.6958 13.222 19.2311 12.8676 19.5683C12.5184 19.9007 12.2269 20 12 20V22C12.8777 22 13.6385 21.5956 14.2464 21.0171C14.8491 20.4435 15.3464 19.6608 15.7422 18.7701C16.5351 16.9861 17 14.5917 17 12H15Z"
                                fill="black" />
                        </svg>
                        Mã lớp: {{ $class->malop }}
                    </div>
                </div>
                <div style="width: 100%; margin: 0; padding: 0;     box-sizing: border-box; ">
                    <form class="choose-type" action="" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="upload-filee">
                            <div class="link">
                                <h1>Tạo bài giảng từ YouTube, Google Drive, Facebook Video,...</h1>
                                <p>Có thể tải nhiều bài giảng video bằng cách nhập link playlist từ Youtube</p>
                                <input id="link-video" name="link_paths" type="text"
                                    placeholder="Nhập link video hoặc playlist tại đây">
                            </div>
                            <div class="file">
                                <h1>Tạo bài giảng từ File</h1>
                                <p>Chỉ hỗ trợ dưới dạng PDF, DOCX, EXCEL, PNG</p>
                                <div>
                                    <input type="file" name="file_paths[]" multiple>
                                </div>
                            </div>
                        </div>
                        <div class="setting">
                            Tên bài giảng
                            <input type="text" name="tenbg" class="field" placeholder="Tên bài giảng">
                            Mô tả
                            <textarea name="noidungbg" class="field-2" placeholder="Mô tả"></textarea>
                            <button type="submit" class="button-donee">Hoàn tất</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    *,
    *::before,
    *::after {
        box-sizing: border-box;
    }


    .lecture-type {
        display: flex;
        width: 100%;
        height: 100%;
        flex-direction: column;
        align-items: flex-start;
        background: #FFF;
    }

    .lecture {
        border-radius: 10px;
        background: #208CE4;
    }

    .lecture a {
        color: #FFF;
        font-family: Inter;
        font-size: 16px;
        font-style: normal;
        font-weight: 700;
        line-height: normal;
    }

    .lecture svg path {
        fill: #FFF;
        stroke: #FFF;
    }

    .body {
        width: 100%;
        flex: 1 0 0;
        display: flex;
        align-items: flex-start;
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

    .class-info {
        display: flex;
        width: 100%;
        flex-direction: column;
        align-items: flex-start;
        gap: 20px;
    }

    .class-name {
        display: flex;
        padding: 20px;
        flex-direction: column;
        align-items: flex-start;
        gap: 20px;
        align-self: stretch;
        border-radius: 10px;
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

    .choose-type {
        display: flex;
        width: 100%;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin: 0;
        padding: 0;
    }

    .upload-filee {
        display: flex;
        width: 70%;
        padding: 20px;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        align-self: stretch;
        border-radius: 10px;
        background: #FFF;
    }

    .link {
        width: 100%;
        display: flex;
        padding: 20px 20px;
        flex-direction: column;
        align-items: center;
        gap: 30px;
        align-self: stretch;
        border-radius: 10px;
        border: 1px solid rgba(0, 60, 60, 0.50);
    }

    .link h1 {
        color: #000;
        text-align: center;
        font-family: Inter;
        font-size: 16px;
        font-style: normal;
        font-weight: 700;
        line-height: normal;
    }

    .link p {
        color: #000;
        text-align: center;
        font-family: Inter;
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: normal;
    }

    .file {
        width: 100%;
        display: flex;
        padding: 20px 20px;
        flex-direction: column;
        align-items: center;
        gap: 30px;
        align-self: stretch;
        border-radius: 10px;
        border: 1px solid rgba(0, 60, 60, 0.50);
    }

    .file h1 {
        color: #000;
        text-align: center;
        font-family: Inter;
        font-size: 16px;
        font-style: normal;
        font-weight: 700;
        line-height: normal;
    }

    .file p {
        color: #000;
        text-align: center;
        font-family: Inter;
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: normal;
    }

    .setting {
        width: 30%;
        display: flex;
        padding: 20px;
        flex-direction: column;
        align-items: flex-start;
        gap: 20px;
        align-self: stretch;
        border-radius: 10px;
        background: #FFF;
        color: #000;
        text-align: center;
        font-family: Inter;
        font-size: 16px;
        font-style: normal;
        font-weight: 700;
        line-height: normal;
    }

    .input-container {
        display: flex;
        align-items: center;
        border: 1px solid #ccc;
        border-radius: 4px;
        padding: 5px;
        width: 100%;
    }

    .input-link {
        border: none;
        outline: none;
        flex: 1;
        padding: 8px;
        font-family: "Inter";
        font-size: 16px;
    }

    .input-container svg {
        margin-left: auto;
        cursor: pointer;
    }

    .field {
        display: flex;
        height: 80px;
        width: 100%;
        align-items: flex-start;
        align-self: stretch;
        border-radius: 10px;
        border: 1px solid rgba(0, 60, 60, 0.50);
        background: #FFF;
        font-family: "Inter";
        font-size: 16px;
        padding: 0 20px;
    }

    .field-2 {
        height: 100%;
        width: 100%;
        align-items: flex-start;
        align-self: stretch;
        border-radius: 10px;
        background: #FFF;
        font-family: "Inter";
        font-size: 16px;
        padding: 10px;
        /* Tạo khoảng cách trên và dưới */
        resize: none;
        /* Không cho phép thay đổi kích thước */
        overflow-wrap: break-word;
        /* Tự động xuống dòng */
        white-space: pre-wrap;
        /* Giữ khoảng trắng và xuống dòng khi cần */
    }

    .button-donee {
        width: 100%;
        display: flex;
        padding: 15px;
        justify-content: center;
        align-items: center;
        gap: 20px;
        border-radius: 10px;
        border: none;
        background: #208CE4;
        color: #FFF;
        font-family: Inter;
        font-size: 16px;
        font-style: normal;
        font-weight: 700;
        line-height: normal;
    }

    .button-donee:hover {
        background: #185ca4;
        cursor: pointer;
    }
</style>
