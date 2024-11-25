<!DOCTYPE html>
<html>
<head>
    <title>{{ $details['subject'] ?? 'Thông báo' }}</title>
</head>
<body>
    <h1>{{ $details['subject'] ?? 'Thông báo từ hệ thống' }}</h1>
    <p>Xin chào,</p>
    <p>Mã đặt chỗ của bạn: {{ $details['booking_code'] ?? 'Không có mã đặt chỗ' }}</p>
    <p>Mã xác nhận hoàn vé: {{ $details['confirmation_code'] ?? 'Không có mã xác nhận' }}</p>
    <p>Vui lòng sử dụng mã này để xác nhận yêu cầu hoàn vé của bạn.</p>
    <p>Trân trọng,</p>
    <p>Đội ngũ hỗ trợ</p>
</body>
</html>
