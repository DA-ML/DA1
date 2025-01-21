<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<style>
    *,
    *:before,
    *:after {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .heading {
        display: flex;
        width: 100vw;
        height: 60px;
        padding: 0px 20px;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid rgba(0, 60, 60, 0.20);
        background: #FFF;
    }

    .heading-img {
        width: 15%;
        height: auto;
        flex-shrink: 0;
    }

    .heading-img img {
        width: 100%;
        height: 100%;
    }

    .heading-dashboard {
        display: flex;
        width: 20%;
        justify-content: space-between;
        align-items: center;
        flex-shrink: 0;
        align-self: stretch;
    }

    .heading-dashboard p {
        color: #000;
        font-family: Inter;
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: normal;
    }

    .heading-dashboard p:hover {
        cursor: pointer;
    }

    .heading-info {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .heading-info p {
        color: #000;
        font-family: Inter;
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: normal;
    }

    .heading-info svg:hover {
        cursor: pointer;
    }

    .dropdown-menu {
        display: none;
        position: absolute;
        width: 200px;
        top: 60px;
        right: 20px;
        background-color: white;
        border: 1px solid rgba(0, 60, 60, 0.2);
        box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
        border-radius: 4px;
        z-index: 1;
    }

    .dropdown-menu a {
        display: flex;
        color: black;
        padding: 10px;
        gap: 20px;
        text-decoration: none;
        font-family: Inter, sans-serif;
        font-size: 14px;
        align-items: center;
    }

    .dropdown-menu a:hover {
        background-color: rgba(0, 60, 60, 0.1);
    }

    form {
        width: 100%;
        padding: 10px;
        font-family: "Inter";
        font-size: 14px;
        display: flex;
        align-items: center;
    }

    form svg {
        margin-right: 20px;
    }

    #logoutForm:hover {
        background-color: rgba(0, 60, 60, 0.1);
        cursor: pointer;
    }

    .heading-dashboard p:hover {
        color: #208ce4;
    }
</style>
<div id="header">
    <div class="heading">
        <div class="heading-img">
            <img src="{{ asset('images/banner_uit.png') }}" alt="Banner">
        </div>
        <div class="heading-dashboard">
            <p id="dashboard">Tổng quan</p>
            <p id="classLink">Lớp học</p>
            <p id="calendarDate">Lịch</p>
        </div>
        <div class="heading-info">
            <a href="#" id="notice">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none">
                    <path d="M4.41406 14.5858L4.80357 14.1963C4.92924 14.0706 5 13.9002 5 13.7224V10C5 6.13401 8.13401 3.00001 12 3C15.866 2.99999 19 6.134 19 10V13.7224C19 13.9001 19.0706 14.0706 19.1963 14.1963L19.5858 14.5858C19.7051 14.7051 19.7649 14.7648 19.8124 14.831C19.9023 14.9561 19.9619 15.1003 19.9868 15.2523C20 15.3328 20.0002 15.4171 20.0002 15.5858C20.0002 15.9714 20.0002 16.1642 19.9478 16.3197C19.848 16.6155 19.6156 16.8477 19.3198 16.9475C19.1643 17 18.9712 17 18.5856 17H5.41406C5.02852 17 4.83568 17 4.68018 16.9475C4.38431 16.8477 4.15225 16.6155 4.05245 16.3196C4 16.1641 4 15.9714 4 15.5858C4 15.4171 4 15.3328 4.0132 15.2523C4.03815 15.1003 4.09766 14.9561 4.1875 14.831C4.23504 14.7648 4.29476 14.7051 4.41406 14.5858ZM15 17V18C15 19.6569 13.6569 21 12 21C10.3431 21 9 19.6569 9 18V17H15Z" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
            <p>{{ session('user.name') }}</p>
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 60 60" fill="none">
                <path
                    d="M43.0416 48.3307C39.8373 44.752 35.1816 42.5 30 42.5C24.8184 42.5 20.1623 44.752 16.958 48.3307M30 52.5C17.5736 52.5 7.5 42.4264 7.5 30C7.5 17.5736 17.5736 7.5 30 7.5C42.4264 7.5 52.5 17.5736 52.5 30C52.5 42.4264 42.4264 52.5 30 52.5ZM30 35C25.8579 35 22.5 31.6421 22.5 27.5C22.5 23.3579 25.8579 20 30 20C34.1421 20 37.5 23.3579 37.5 27.5C37.5 31.6421 34.1421 35 30 35Z"
                    stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <svg xmlns="http://www.w3.org/2000/svg" id="toggleDropdown" width="24" height="24"
                viewBox="0 0 24 24" fill="none">
                <path d="M19 9L12 16L5 9" stroke="black" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
            <div class="dropdown-menu" id="dropdownMenu">
                <a href="#" id="viewProfile">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none">
                        <path
                            d="M20 21C20 18.2386 16.4183 16 12 16C7.58172 16 4 18.2386 4 21M12 13C9.23858 13 7 10.7614 7 8C7 5.23858 9.23858 3 12 3C14.7614 3 17 5.23858 17 8C17 10.7614 14.7614 13 12 13Z"
                            stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Thông tin cá nhân
                </a>
                <form action="{{ route('logout') }}" method="POST" id="logoutForm" onclick="confirmLogout()">
                    @csrf
                    <div style="cursor: pointer; display: flex; align-items: center;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none">
                            <path
                                d="M12 15L15 12M15 12L12 9M15 12L4 12M4 17C4 17.9319 4 18.3978 4.15224 18.7654C4.35523 19.2554 4.74481 19.6448 5.23486 19.8478C5.6024 20 6.06812 20 7 20H16.8C17.9201 20 18.48 20 18.9078 19.782C19.2841 19.5902 19.5905 19.2844 19.7822 18.908C20.0002 18.4802 20 17.9201 20 16.8V7.19995C20 6.07985 20.0002 5.51986 19.7822 5.09204C19.5905 4.71572 19.2841 4.40973 18.9078 4.21799C18.48 4 17.9201 4 16.8 4H7C6.06812 4 5.60241 4 5.23486 4.15224C4.74481 4.35523 4.35523 4.74456 4.15224 5.23462C4 5.60216 4 6.0681 4 6.99999"
                                stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        Đăng xuất
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const role = "{{ session('user.role') }}";
    // JavaScript to toggle dropdown visibility
    const toggleDropdown = document.getElementById('toggleDropdown');
    const dropdownMenu = document.getElementById('dropdownMenu');

    toggleDropdown.addEventListener('click', () => {
        // Toggle the dropdown visibility
        dropdownMenu.style.display = dropdownMenu.style.display === 'none' ? 'block' : 'none';
    });

    // Close the dropdown if clicking outside
    window.addEventListener('click', function(e) {
        if (!toggleDropdown.contains(e.target) && !dropdownMenu.contains(e.target)) {
            dropdownMenu.style.display = 'none';
        }
    });

    document.getElementById('classLink').addEventListener('click', function() {
        if (role === 'giaovien') {
            window.location.href = '/teacher/classlist';
        } else if (role === 'sinhvien') {
            window.location.href = '/student/classlist';
        }
    });

    document.getElementById('dashboard').addEventListener('click', function() {
        if (role === 'giaovien') {
            window.location.href = '/teacher/dashboard';
        } else if (role === 'sinhvien') {
            window.location.href = '/student/dashboard';
        }
    });

    document.getElementById('viewProfile').addEventListener('click', function() {
        if (role === 'giaovien') {
            window.location.href = '/teacher/profile';
        } else if (role === 'sinhvien') {
            window.location.href = '/student/profile';
        }
    });

    document.getElementById('calendarDate').addEventListener('click', function() {
        if (role === 'giaovien') {
            window.location.href = '/teacher/calendar';
        } else if (role === 'sinhvien') {
            window.location.href = '/student/calendar';
        }
    });

    document.getElementById('notice').addEventListener('click', function() {
        if (role === 'sinhvien') {
            window.location.href = '/student/notice';
        }
    });

    document.getElementById('logoutForm').addEventListener('click', function() {
        this.submit(); // Tự động gửi form khi nhấn vào
    });

    function confirmLogout() {
        if (confirm("Bạn có chắc chắn muốn đăng xuất không?")) {
            document.getElementById("logoutForm").submit()
        } else {
            console.log("Người dùng đã hủy.");
        }
    }
</script>
