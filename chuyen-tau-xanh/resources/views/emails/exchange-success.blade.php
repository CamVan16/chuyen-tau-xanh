<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông báo đổi vé thành công</title>
</head>
<body>
    <h1>Chào bạn</h1>
    <p>Yêu cầu đổi vé của bạn đã được xử lý thành công.</p>
    <ul>
        <li><strong>Mã vé cũ:</strong> {{ $newExchange->old_ticket_id }}</li>
        <li><strong>Mã đặt vé mới:</strong> {{ $newExchange->newBookingId }}</li>
        <li><strong>Mã vé mới:</strong> {{ $newExchange->new_ticket_id }}</li>
        <li><strong>Tiền phải trả:</strong> {{ number_format($newExchange->additional_price ?? 0, 0, ',', '.') }} VND</li>
        <li><strong>Phương thức thanh toán:</strong> {{ $newExchange->payment_method }}</li>
    </ul>
    <p>Nếu bạn có thắc mắc, vui lòng liên hệ với chúng tôi.</p>
    <p>Trân trọng,</p>
    <p>Đội ngũ hỗ trợ khách hàng</p>
</body>
</html>
