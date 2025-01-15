<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login page</title>
</head>

<body>
    <div class="login">
        <div class="heading-banner">
            <img src="{{ asset('images/banner_uit.png') }}" alt="Banner">
            <div style="display: flex; gap: 20px;">
            </div>
        </div>
        <div class="body">
            <div class="content">
                <div class="form">
                    <div class="login_form">
                        <div style="width: 100%; display: flex; justify-content: center;">
                            <h1>ĐĂNG NHẬP</h1>
                        </div>
                        <!-- Form đăng nhập -->
                        <form method="POST" action="{{ url('login') }}">
                            @csrf
                            <p>Username</p>
                            <input type="text" name="username" placeholder="Username" required>
                            <p>Password</p>
                            <input type="password" name="password" placeholder="Password" required>
                            <button type="submit" name="login_btn" class="btn-login">
                                Đăng nhập
                            </button>
                        </form>
                        <!-- Thông báo lỗi -->
                        @if (session('alert'))
                            <script type="text/javascript">
                                alert("{{ session('alert') }}");
                            </script>
                        @endif
                    </div>
                </div>
                <div class="picture">
                    <img src="{{ asset('images/student-illustration.svg') }}" alt="Login picture">
                </div>
            </div>
        </div>
    </div>
    <style>
        *,
        *:before,
        *:after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Inter", sans-serif;
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #dedede;
        }

        img {
            max-width: 100%;
            height: 100%;
        }

        :root {
            --darkblue: #185ca4;
            --lightblue: #208ce4;
            --smooth: all ease 0.2s;
        }

        .login {
            display: flex;
            width: 100%;
            height: 100%;
            flex-direction: column;
            align-items: flex-start;
        }

        .heading-banner {
            display: flex;
            padding: 0px 20px;
            justify-content: space-between;
            align-items: center;
            background-color: white;
            width: 100%;
            border-bottom: 1px solid rgba(0, 60, 60, 0.20);
            position: fixed;
        }

        .heading-banner img {
            width: 20%;
            height: 80px;
        }

        .body {
            display: flex;
            justify-content: center;
            align-items: center;
            flex: 1 0 0;
            align-self: stretch;
            background-color: white;
        }

        .content {
            display: flex;
            width: 80%;
            justify-content: center;
            align-items: center;
            gap: 20px;
        }

        .form {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            width: 100%;
        }

        .picture {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-shrink: 0;
        }

        .picture img {
            display: flex;
            width: 100%;
            height: 100%;
            justify-content: center;
            align-items: center;
        }

        .login_form {
            display: flex;
            width: 550px;
            height: auto;
            flex-direction: column;
            justify-content: space-between;
            align-items: stretch;
            gap: 20px;
        }

        .login_form h1 {
            color: #208ce4;
            font-size: 48px;
            font-weight: 900;
        }

        .login_form input {
            display: flex;
            width: 100%;
            height: 50px;
            padding: 15px;
            margin-top: 15px;
            margin-bottom: 25px;
            align-items: center;
            border-radius: 10px;
            border: 1px solid rgba(0, 0, 0, 0.2);
            background: #fff;
            color: #000;
            font-size: 16px;
            outline: none;
        }

        .login_form input[type="submit"] {
            width: 100%;
            /* Sửa lại giá trị width */
            height: 60px;
            border-radius: 10px;
            border: none;
            color: white;
            background: #208ce4;
            font-size: 28px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .btn-login {
            background-color: #208CE4;
            height: 50px;
            font-family: Inter;
            font-size: 20px;
            font-style: normal;
            font-weight: 700;
            line-height: normal;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            width: 100%;
            cursor: pointer;
        }

        .btn-login:hover {
            background-color: #004b9a;
        }
    </style>
</body>

</html>
