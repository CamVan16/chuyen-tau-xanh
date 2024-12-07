<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Ticket</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center">Giả lập Đặt Vé</h1>
        <form action="{{ route('booking.processPayment') }}" method="POST" class="mt-4">
            @csrf
            <div class="mb-3">
                <label for="order_id" class="form-label">Booking ID (Order ID):</label>
                <input type="text" name="order_id" id="order_id" class="form-control" value="BK202412132" required>
            </div>
            <div class="mb-3">
                <label for="amount" class="form-label">Tổng tiền (Total Price):</label>
                <input type="number" name="amount" id="amount" class="form-control" value="2000000" required>
            </div>
            <div class="mb-3">
                <label for="order_desc" class="form-label">Mô tả (Order Description):</label>
                <input type="text" name="order_desc" id="order_desc" class="form-control" value="Thanh toán vé tàu Tết" required>
            </div>
            <div class="mb-3">
                <label for="order_type" class="form-label">Loại đơn hàng (Order Type):</label>
                <input type="text" name="order_type" id="order_type" class="form-control" value="billpayment" required>
            </div>
            <div class="mb-3">
                <label for="language" class="form-label">Ngôn ngữ (Language):</label>
                <select name="language" id="language" class="form-select">
                    <option value="vn">Tiếng Việt</option>
                    <option value="en">English</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="bank_code" class="form-label">Ngân hàng (Bank Code):</label>
                <select name="bank_code" id="bank_code" class="form-select">
                    <option value="">Không chọn</option>
                    <option value="NCB">Ngân hàng NCB</option>
                    <option value="VCB">Ngân hàng Vietcombank</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="payment_method" class="form-label">Phương thức thanh toán (Payment Method):</label>
                <select name="payment_method" id="payment_method" class="form-select" required>
                    <option value="vnpay">VNPay</option>
                    <option value="zalopay">ZaloPay</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Thanh Toán</button>
        </form>
        
    </div>
</body>

</html>