<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chuyến tàu xanh</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <style>
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
            max-height: 80px;
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
            justify-content: center;
            align-items: center;
            width: 100%;
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

        .footer {
            background-color: #42aaf5;
            color: white;
            text-align: center;
            padding: 20px 0;
            margin-top: 30px;
            bottom: 0;
        }

        .footer a {
            color: white;
            text-decoration: none;
        }

        .footer a:hover {
            color: #ffd700;
        }

        .footer p {
            margin: 0;
            font-size: 14px;
        }

        .payment-icons {
            margin-top: 20px;
        }

        .payment-icons i {
            font-size: 30px;
            margin: 0 15px;
            color: white;
            transition: color 0.3s;
        }

        .payment-icons i:hover {
            color: #ffd700;
        }
    </style>
</head>

<body>
    <header class="header">
        <div class="header-top">
            <img src="/logo.png" alt="Chuyến tàu xanh" class="logo">
            <span class="date"></span>
        </div>
        <nav class="navbar">
            <ul>
                <li><a href="#">Trang chủ</a></li>
                <li><a href="#">Tìm vé</a></li>
                <li><a href="#">Thông tin đặt chỗ</a></li>
                <li><a href="/tra-ve">Trả vé</a></li>
                <li><a href="#">Kiểm tra vé</a></li>
                <li><a href="#">Giờ tàu - Giá vé</a></li>
                <li><a href="#">Khuyến mại</a></li>
                <li><a href="/quy-dinh">Các quy định</a></li>
                <li><a href="/huong-dan">Hướng dẫn</a></li>
                <li><a href="#">Liên hệ</a></li>
            </ul>
        </nav>
    </header>

    <div class="container mt-3">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2024 Chuyến tàu xanh. All rights reserved.</p>
        <p><a href="#">Chính sách bảo mật</a> | <a href="#">Điều khoản sử dụng</a></p>
        <p>Liên hệ: <a href="mailto:7radiante@gmail.com">info@chuyentauxanh.com</a></p>

        <!-- Payment Methods Icons -->
        <div class="payment-icons">
            <i class="fab fa-cc-visa"></i>
            <i class="fab fa-cc-mastercard"></i>
            <i class="fab fa-cc-paypal"></i>
            <i class="fab fa-apple-pay"></i>
            <i class="fab fa-google-pay"></i>
            <i class="fab fa-amazon-pay"></i>
        </div>
    </footer>

    <script>
        const currentDate = new Date();
        const daysOfWeek = ['Chủ nhật', 'Thứ hai', 'Thứ ba', 'Thứ tư', 'Thứ năm', 'Thứ sáu', 'Thứ bảy'];
        const monthsOfYear = ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8',
            'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'
        ];
        const dayOfWeek = daysOfWeek[currentDate.getDay()];
        const day = currentDate.getDate();
        const month = monthsOfYear[currentDate.getMonth()];
        const year = currentDate.getFullYear();
        const formattedDate = `${dayOfWeek}, ${day} ${month} ${year}`;
        document.querySelector('.date').textContent = formattedDate;
    </script>
</body>

</html>
