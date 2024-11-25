<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mã Đặt Chỗ</title>
</head>
<body>
    <p>Chúng tôi xin gửi mã đặt chỗ của bạn:</p>
    <p><strong>Mã đặt chỗ: {{ $booking->id }}</strong></p>
    <p><strong>Ngày đặt chỗ: {{ $booking->booked_time }}</strong></p>
    <p>Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi.</p>

    <p>Trân trọng,</p>
    <p><strong>Hệ thống bán vé</strong></p>
</body>
</html>
