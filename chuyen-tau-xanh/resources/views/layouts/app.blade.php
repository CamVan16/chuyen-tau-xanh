<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @yield('head')
    <title>Chuyến tàu xanh</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <style>
        /* Cấu trúc Flexbox cho body để footer luôn ở dưới cùng */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Đảm bảo chiều cao của body là 100% chiều cao màn hình */
        }

        .container {
            flex: 1; /* Chiếm phần còn lại của trang */
        }

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
            background-color: rgb(236, 246, 251);
            color: #008ecf;
            padding: 20px 20px 10px;
            margin-top: 30px;
        }

        .footer .footer-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 20px;
            flex: 1;
            margin-left: 100px;
        }

        .footer .footer-column {
            flex: 1;
        }

        .footer h4 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .footer ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer ul li {
            margin-bottom: 10px;
        }

        .footer ul li a {
            color: #42aaf5;
            text-decoration: none;
            font-size: 14px;
        }

        .footer ul li a:hover {
            color: #ffd700;
        }

        .footer .payment-icons {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .footer .payment-icons img {
            height: 32px;
        }

        .footer .social-icons {
            display: flex;
            gap: 10px;
        }

        .footer .social-icons{
            width: 50px;
            transition: color 0.3s;
        }

        .footer .app-buttons img {
            height: 40px;
            margin-top: 10px;
        }

        .footer .copyright {
            font-size: 12px;
            text-align: center;
            border-top: 1px solid black;
            padding-top: 10px;
            margin-top: 10px;
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
                <li><a href="/tim-cho">Trang chủ</a></li>
                <li><a href="/tim-cho">Tìm vé</a></li>
                <li><a href="/thong-tin-dat-cho">Thông tin đặt chỗ</a></li>
                <li><a href="/tra-ve">Trả vé</a></li>
                <li><a href="/kiem-tra-ve">Kiểm tra vé</a></li>
                <li><a href="/doi-ve">Đổi vé</a></li>
                <li><a href="/giotau-giave">Giờ tàu - Giá vé</a></li>
                <li><a href="/khuyen-mai">Khuyến mại</a></li>
                <li><a href="/quy-dinh">Các quy định</a></li>
                <li><a href="/huong-dan">Hướng dẫn</a></li>
                <li><a href="/lien-he">Liên hệ</a></li>
            </ul>
        </nav>
    </header>

    <div class="container mt-3">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-column">
                <img src="/logo.png" alt="Logo" style="height: 50px; margin-bottom: 20px;">
                <ul>
                    <li><a href="/huong-dan"><i class="fa fa-chevron-right"></i> Hướng dẫn đặt vé</a></li>
                    <li><a href="/lien-he"><i class="fa fa-chevron-right"></i> Liên hệ chúng tôi</a></li>
                    <li><a href="/huong-dan"><i class="fa fa-chevron-right"></i> Trợ giúp</a></li>
                    <li><a href="#"><i class="fa fa-chevron-right"></i> Về chúng tôi</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h4>QUY ĐỊNH VÀ ĐIỀU KHOẢN</h4>
                <ul>
                    <li><a href="/quy-dinh"><i class="fa fa-chevron-right"></i> Chính sách vận chuyển hành khách</a></li>
                    <li><a href="/quy-dinh"><i class="fa fa-chevron-right"></i> Chính sách đổi trả, hoàn vé</a></li>
                    <li><a href="/quy-dinh"><i class="fa fa-chevron-right"></i> Chính sách bảo mật</a></li>
                    <li><a href="/quy-dinh"><i class="fa fa-chevron-right"></i> Chính sách & Quy định chung</a></li>
                    <li><a href="/quy-dinh"><i class="fa fa-chevron-right"></i> Hướng dẫn thanh toán</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h4>THEO DÕI CHÚNG TÔI TRÊN</h4>
                <div class="social-icons mb-4">
                    <img src="/facebook.svg" alt="Facebook">
                    <img src="/youtube.svg" alt="Youtube">
                    <img src="/zalo.svg" alt="Zalo">
                </div>
                <h4>ĐỐI TÁC THANH TOÁN</h4>
                <div class="payment-icons">
                    <img src="/visa.png" alt="Visa">
                    <img src="/mastercard.png" alt="Mastercard">
                    <img src="/JCB.png" alt="JCB">
                    <img src="/zalopay.png" alt="Zalopay">
                    <img src="/momo.png" alt="Momo">
                </div>
            </div>
        </div>
        <div class="copyright">
            &copy; 2024 Chuyến tàu xanh. All rights reserved.
        </div>
    </footer>

    <script>
        const currentDate = new Date();
        const daysOfWeek = ['Chủ nhật', 'Thứ hai', 'Thứ ba', 'Thứ tư', 'Thứ năm', 'Thứ sáu', 'Thứ bảy'];
        const monthsOfYear = ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'];
        const dayOfWeek = daysOfWeek[currentDate.getDay()];
        const day = currentDate.getDate();
        const month = monthsOfYear[currentDate.getMonth()];
        const year = currentDate.getFullYear();
        const formattedDate = `${dayOfWeek}, ${day} ${month} ${year}`;
        document.querySelector('.date').textContent = formattedDate;
    </script>
</body>

</html>
