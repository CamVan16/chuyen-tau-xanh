<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông Tin Liên Hệ</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h2 {
            color: #007bff;
        }
        .contact-info {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        p {
            margin: 8px 0;
        }
        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #555;
        }
    </style>
</head>
<body>
    <h2>Thông Tin Liên Hệ Người Dùng</h2>
    <div class="contact-info">
        <p><strong>Họ và Tên:</strong>  {{ $data['name'] }}</p>
        <p><strong>Email:</strong>  {{ $data['email'] }}</p>
        <p><strong>Nội dung:</strong></p>
        <p> {{ $data['message'] }}</p>
    </div>
    <div class="footer">
        <p>Cảm ơn bạn đã liên hệ với chúng tôi. Chúng tôi sẽ phản hồi trong thời gian sớm nhất.</p>
    </div>
</body>
</html>
