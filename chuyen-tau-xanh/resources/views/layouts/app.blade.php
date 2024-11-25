<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        /* Header styling */
        .header {
            background-color: #008ecf;
            color: white;
        }

        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
        }

        .header-top img.logo {
            max-height: 50px;
        }

        .header-top .date {
            font-size: 16px;
        }

        .navbar {
            background-color: #42aaf5;
        }

        .navbar ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: space-evenly;
            align-items: center;
        }

        .navbar ul li {
            padding: 10px 20px;
        }

        .navbar ul li a {
            text-decoration: none;
            color: white;
            font-size: 14px;
            font-weight: bold;
            transition: color 0.3s;
        }

        .navbar ul li a:hover {
            color: #ffd700;
        }

        .navbar .flag-icon {
            max-height: 15px;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-top">
            <img src="/path-to-your-logo/logo.png" alt="Logo" class="logo">
            <span class="date">Thứ hai, 25/11/2024</span>
        </div>
        <nav class="navbar">
            <ul>
                <li><a href="#">Trang chủ</a></li>
                <li><a href="#">Tìm vé</a></li>
                <li><a href="#">Thông tin đặt chỗ</a></li>
                <li><a href="#">Trả vé</a></li>
                <li><a href="#">Kiểm tra vé</a></li>
                <li><a href="#">Giờ tàu - Giá vé</a></li>
                <li><a href="#">Khuyến mại</a></li>
                <li><a href="#">Các quy định</a></li>
                <li><a href="#">Hướng dẫn</a></li>
                <li><a href="#">Liên hệ</a></li>
            </ul>
        </nav>
    </header>
    <div class="container mt-3">
        @yield('content')
    </div>
</body>
</html>
