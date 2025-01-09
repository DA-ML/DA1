<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Teacher Password</title>
</head>
<link rel="stylesheet" href="{{ asset('css/global.css') }}">

<div class="update-pw">
    @include('components.heading')
    <div class="body">
        <div class="content">
            <div class="header">
                <div class="header-content">Chỉnh sửa mật khẩu</div>
            </div>
            @if (session('success'))
                <script>
                    alert('{{ session('success') }}');
                </script>
            @endif

            @if (session('error'))
                <script>
                    alert('{{ session('error') }}');
                </script>
            @endif

            <div class="info">
                <form method="POST" action="{{ route('teacher.password.change') }}">
                    @csrf
                    <div class="change_pw">
                        <p>Nhập mật khẩu cũ: </p>
                        <div class="changepw_input">
                            <input type="password" name="pass_old" placeholder="Mật khẩu cũ" required />
                        </div>
                        <p>Nhập mật khẩu mới:</p>
                        <div class="changepw_input">
                            <input type="password" name="pass_new" placeholder="Mật khẩu mới" required />
                        </div>
                        <p>Xác nhận mật khẩu mới:</p>
                        <div class="changepw_input">
                            <input type="password" name="pass_newcf" placeholder="Xác nhận mật khẩu mới" required />
                        </div>
                        <div class="changepw_btn">
                            <button type="submit" class="primary"
                                style="width: 50%; text-align:center; align-items: center; justify-content: center;">GỬI</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .update-pw {
        display: flex;
        width: 100%;
        height: 100%;
        flex-direction: column;
        align-items: center;
    }

    .heading-dashboard p:nth-child(2) {
        color: #208CE4;
        font-weight: 700;
    }
    
    .body {
        display: flex;
        justify-content: center;
        align-items: center;
        flex: 1 0 0;
        align-self: stretch;
        background: #F0F2F5;
    }

    .content {
        display: flex;
        width: 100%;
        max-width: 600px;
        flex-direction: column;
        align-items: center;
        align-self: stretch;
    }

    .header {
        width: 100%;
        display: flex;
        height: auto;
        padding: 10px 20px;
        justify-content: space-between;
        align-items: center;
        align-self: stretch;
        border: 1px solid #F0F2F5;
        background: #FFF;
    }

    .header-content {
        color: #000;
        font-family: Inter;
        font-size: 20px;
        font-style: normal;
        font-weight: 500;
        line-height: normal;
    }

    .info {
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: stretch;
        gap: 20px;
        border-radius: 0px 0px 10px 10px;
        flex: none;
        align-self: stretch;
        background: #FFF;
        color: #000;
        font-family: Inter;
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: normal;
    }

    .change_pw {
        background-color: white;
        width: 95%;
        max-width: 400px;
        align-items: center;
        justify-content: space-between;
        margin: auto;
        border-radius: 10px;
    }

    .change_pw p {
        font-weight: 600;
        font-size: 16px;
        margin: 10px 0;
    }

    .changepw_input input {
        width: 100%;
        max-width: 400px;
        padding: 10px 10px 10px 40px;
        border: 1px solid #ddd;
        border-radius: 5px;
        box-sizing: border-box;
        margin: 10px 0;
        color: black;
        font-family: "Inter";
    }

    .changepw_btn {
        width: 100%;
        height: 40px;
        margin-top: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 20px;
    }
</style>
