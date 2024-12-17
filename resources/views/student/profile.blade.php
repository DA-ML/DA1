<link rel="stylesheet" href="{{ asset('css/global.css') }}">

<div class="view-profile">
    @include('components.heading')
    <div class="body">
        <div class="content">
            <div class="header">
                <div class="header-content">Hồ sơ của tôi</div>
                @include('components.button_check')
            </div>
            <div class="info">
                <div class="info-pic">
                    <img src="{{ asset('images/ava.png') }}" alt="avatar">
                </div>
                <div class="info-heading">
                    Thông tin tài khoản
                </div>
                <div class="info-table">
                    <div class="deeper-table">
                        <div class="table">
                            <div class="row-1">
                                <div class="cell-1">
                                    <div class="cell-content">
                                        <div class="cell-user">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 20 20" fill="none">
                                                <path
                                                    d="M15 19C15 16.2386 12.7614 14 10 14C7.23858 14 5 16.2386 5 19M10 11C8.34315 11 7 9.65685 7 8C7 6.34315 8.34315 5 10 5C11.6569 5 13 6.34315 13 8C13 9.65685 11.6569 11 10 11ZM19 4.19995V15.8C19 16.9201 19.0002 17.4802 18.7822 17.908C18.5905 18.2844 18.2841 18.5902 17.9078 18.782C17.48 19 16.9203 19 15.8002 19H4.2002C3.08009 19 2.51962 19 2.0918 18.782C1.71547 18.5902 1.40973 18.2844 1.21799 17.908C1 17.4802 1 16.9201 1 15.8V4.19995C1 3.07985 1 2.51986 1.21799 2.09204C1.40973 1.71572 1.71547 1.40973 2.0918 1.21799C2.51962 1 3.08009 1 4.2002 1H15.8002C16.9203 1 17.48 1 17.9078 1.21799C18.2841 1.40973 18.5905 1.71572 18.7822 2.09204C19.0002 2.51986 19 3.07985 19 4.19995Z"
                                                    stroke="white" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="cell-2">
                                    <div class="cell-content">Tên đăng nhập</div>
                                </div>
                                <div class="cell-2">
                                    <div class="cell-content">{{ $user['id'] }}</div>
                                </div>
                                <div class="cell-2">
                                    <div class="cell-content"></div>
                                </div>
                            </div>
                            <div class="row-1">
                                <div class="cell-1">
                                    <div class="cell-content">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M4 6L12.2286 12L19.9999 6M21 8.19995V15.8C21 16.9201 21.0002 17.4802 20.7822 17.908C20.5905 18.2844 20.2841 18.5902 19.9078 18.782C19.48 19 18.9203 19 17.8002 19H6.2002C5.08009 19 4.51962 19 4.0918 18.782C3.71547 18.5902 3.40973 18.2844 3.21799 17.908C3 17.4802 3 16.9201 3 15.8V8.19995C3 7.07985 3 6.51986 3.21799 6.09204C3.40973 5.71572 3.71547 5.40973 4.0918 5.21799C4.51962 5 5.08009 5 6.2002 5H17.8002C18.9203 5 19.48 5 19.9078 5.21799C20.2841 5.40973 20.5905 5.71572 20.7822 6.09204C21.0002 6.51986 21 7.07985 21 8.19995Z"
                                                stroke="#185CA4" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="cell-2">
                                    <div class="cell-content">Email</div>
                                </div>
                                <div class="cell-2">
                                    <div class="cell-content">{{ $user['email'] }}</div>
                                </div>
                                <div class="cell-2">
                                    <div classcell-content></div>
                                </div>
                            </div>
                            <div class="row-1">
                                <div class="cell-1">
                                    <div class="cell-content">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M4 17.8V12.2C4 11.0798 4 10.5199 4.21799 10.092C4.40973 9.71572 4.71547 9.40973 5.0918 9.21799C5.51962 9 6.08009 9 7.2002 9H16.8002C17.9203 9 18.48 9 18.9078 9.21799C19.2841 9.40973 19.5905 9.71572 19.7822 10.092C20.0002 10.5199 20 11.0798 20 12.2V17.8C20 18.9201 20.0002 19.4802 19.7822 19.908C19.5905 20.2844 19.2841 20.5902 18.9078 20.782C18.48 21 17.9203 21 16.8002 21H7.2002C6.08009 21 5.51962 21 5.0918 20.782C4.71547 20.5902 4.40973 20.2844 4.21799 19.908C4 19.4802 4 18.9201 4 17.8ZM9 8.76923V6C9 4.34315 10.3431 3 12 3C13.6569 3 15 4.34315 15 6V8.76923C15 8.89668 14.8964 9 14.7689 9H9.23047C9.10302 9 9 8.89668 9 8.76923Z"
                                                stroke="#D9181B" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="cell-2">
                                    <div class="cell-content">Mật khẩu</div>
                                </div>
                                <div class="cell-2">
                                    <div class="cell-content">******</div>
                                </div>
                                <div class="cell-2">
                                    <div class="cell-content-2">Chỉnh sửa</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="info-middle">
                    Thông tin cá nhân
                </div>
                <div class="info-table">
                    <div class="deeper-table">
                        <div class="table">
                            <div class="row-1">
                                <div class="cell-1">
                                    <div class="cell-content">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M20 21C20 18.2386 16.4183 16 12 16C7.58172 16 4 18.2386 4 21M12 13C9.23858 13 7 10.7614 7 8C7 5.23858 9.23858 3 12 3C14.7614 3 17 5.23858 17 8C17 10.7614 14.7614 13 12 13Z"
                                                stroke="#185CA4" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="cell-2">
                                    <div class="cell-content">Họ và tên</div>
                                </div>
                                <div class="cell-2">
                                    <div class="cell-content">{{ $user['name'] }}</div>
                                </div>
                                <div class="cell-2">
                                    <div class="cell-content"></div>
                                </div>
                            </div>
                            <div class="row-1">
                                <div class="cell-1">
                                    <div class="cell-content">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M12 8.19444C10 3.5 3 4 3 10C3 16.0001 12 21 12 21C12 21 21 16.0001 21 10C21 4 14 3.5 12 8.19444Z"
                                                stroke="#D9181B" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="cell-2">
                                    <div class="cell-content">Ngày sinh</div>
                                </div>
                                <div class="cell-2">
                                    <div class="cell-content">{{ $user['date'] }}</div>
                                </div>
                                <div class="cell-2">
                                    <div classcell-content></div>
                                </div>
                            </div>
                            <div class="row-1">
                                <div class="cell-1">
                                    <div class="cell-content">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M2 20H22M20 11.4522V20.0001L14 20V16C14 14.8954 13.1046 14 12 14C10.8954 14 10 14.8954 10 16V20H4V11.4522C4 10.9178 4 10.6506 4.06497 10.402C4.12255 10.1816 4.2173 9.97269 4.34521 9.78427C4.48955 9.57164 4.69064 9.39575 5.09277 9.04389L9.89278 4.84389C10.6394 4.1906 11.0125 3.864 11.4326 3.73976C11.8028 3.63028 12.1972 3.63028 12.5674 3.73976C12.9875 3.864 13.3608 4.1906 14.1074 4.84389L18.9074 9.04389C19.3096 9.39575 19.5104 9.57164 19.6548 9.78426C19.7827 9.97269 19.8775 10.1816 19.9351 10.402C20 10.6506 20 10.9178 20 11.4522Z"
                                                stroke="#185CA4" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="cell-2">
                                    <div class="cell-content">Hệ đào tạo</div>
                                </div>
                                <div class="cell-2">
                                    <div class="cell-content">{{ $user['system'] }}</div>
                                </div>
                                <div class="cell-2">
                                    <div class="cell-content-2"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .view-profile {
        display: flex;
        width: 100%;
        height: 100%;
        flex-direction: column;
        align-items: flex-start;
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
        width: 80%;
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
        padding-top: 28px;
        flex-direction: column;
        align-items: center;
        gap: 20px;
        flex: 1 0 0;
        align-self: stretch;
        background: #FFF;
    }

    .info-pic {
        width: 100%;
        display: flex;
        height: 100px;
        justify-content: center;
        align-items: center;
    }

    .info-pic img {
        width: 100px;
        height: 100px;
    }

    .info-heading {
        width: 100%;
        display: flex;
        padding: 0px 20px;
        align-items: center;
        align-self: stretch;
        color: #000;
        font-family: Inter;
        font-size: 20px;
        font-style: normal;
        font-weight: 500;
        line-height: normal;
    }

    .info-table {
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 112px;
        align-self: stretch;
    }

    .deeper-table {
        width: 100%;
        display: flex;
        height: 156px;
        flex-direction: column;
        justify-content: space-between;
        align-items: flex-start;
    }

    .table {
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        border-radius: 4px;
        border: 1px solid #FFF;
        background: #FFF;
    }

    .row-1 {
        display: flex;
        width: 100%;
        align-items: flex-start;
        background: rgba(255, 255, 255, 0.00);
    }

    .cell-1 {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        align-self: stretch;
        border-top: 1px solid rgba(0, 60, 60, 0.50);
    }

    .cell-content {
        display: flex;
        height: 52px;
        padding: 16px;
        align-items: center;
        color: rgba(32, 34, 36, 0.80);
        font-family: Inter;
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: 130%;
        /* 20.8px */
    }

    .cell-user {
        display: flex;
        padding: 3px;
        align-items: center;
        gap: 10px;
        background: #000;
    }

    .cell-2 {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: flex-start;
        flex: 1 0 0;
        align-self: stretch;
        border-top: 1px solid rgba(0, 60, 60, 0.50);
        background: rgba(255, 255, 255, 0.00);
    }

    .cell-content-2 {
        display: flex;
        height: 52px;
        padding: 16px;
        justify-content: flex-end;
        align-items: center;
        align-self: stretch;
        color: #208CE4;
        font-family: Inter;
        font-size: 16px;
        font-style: normal;
        font-weight: 500;
        line-height: 130%;
        /* 20.8px */
    }

    .info-middle {
        display: flex;
        padding: 0px 20px;
        align-items: flex-start;
        align-self: stretch;
        color: #000;
        font-family: Inter;
        font-size: 20px;
        font-style: normal;
        font-weight: 500;
        line-height: normal;
    }
</style>
