<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Trang chủ')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .header {
            background-color: #0072c6;
            color: white;
            padding: 10px 0;
        }
        .header a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
        }
        .header a:hover {
            text-decoration: underline;
        }
        .header .logo img {
            height: 50px;
        }
        .header .language {
            text-align: right;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div class="logo d-flex align-items-center">
                    <img src="https://via.placeholder.com/50x50" alt="Logo">
                    <span class="ms-2">ĐƯỜNG SẮT VIỆT NAM</span>
                </div>
                <div class="language">
                    <a href="#">English</a>
                    <img src="https://upload.wikimedia.org/wikipedia/en/thumb/a/a4/Flag_of_the_United_Kingdom.svg/25px-Flag_of_the_United_Kingdom.svg.png" alt="English">
                </div>
            </div>
            <nav class="mt-3">
                <a href="#">TÌM VÉ</a>
                <a href="#">THÔNG TIN ĐẶT CHỖ</a>
                <a href="#">TRẢ VÉ</a>
                <a href="#">KIỂM TRA VÉ</a>
                <a href="#">GIỜ TÀU - GIÁ VÉ</a>
                <a href="#">KHUYẾN MẠI</a>
                <a href="#">CÁC QUY ĐỊNH</a>
                <a href="#">HƯỚNG DẪN</a>
                <a href="#">LIÊN HỆ</a>
            </nav>
        </div>
    </header>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
