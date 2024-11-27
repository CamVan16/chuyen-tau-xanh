<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận trả vé thành công</title>
</head>
<body>
    <h1>Xác nhận trả vé thành công</h1>
    <p>Xin chào {{ $refund->booking->customer->customer_name }},</p>

    <p>Yêu cầu hoàn vé của bạn đã được xử lý thành công.</p>
    <p><strong>Mã đặt vé:</strong> {{ $refund->booking->id }}</p>
    <p><strong>Số tiền hoàn:</strong> {{ number_format($refund->refund_amount, 2) }} {{ $refund->currency }}</p>
    <p><strong>Ngày thực hiện hoàn vé:</strong> {{ $refund->updated_at->format('d-m-Y H:i:s') }}</p>

    <p>Vé của bạn sẽ được hoàn trong vòng 03 ngày làm việc. Nếu có bất kỳ thắc mắc nào, vui lòng liên hệ với chúng tôi qua hotline hoặc email hỗ trợ.</p>

    <p>Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi!</p>
</body>
</html>
