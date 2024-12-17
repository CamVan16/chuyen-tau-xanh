<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách mã đặt chỗ</title>
</head>
<body>
    <h1>Danh sách mã đặt chỗ của bạn</h1>
    <p>Xin chào,</p>
    <p>Dưới đây là danh sách các mã đặt chỗ của bạn:</p>

    <ul>
        @foreach ($bookings as $booking)
            <li>Mã đặt chỗ: {{ $booking->id }} (Khách hàng: {{ $booking->customer->customer_name }})</li>
        @endforeach
    </ul>

    <p>Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi.</p>
</body>
</html>
