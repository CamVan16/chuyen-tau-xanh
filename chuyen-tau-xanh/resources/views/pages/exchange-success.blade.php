@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <!-- Success Message -->
    <h2 class="text-primary text-center mb-4">XÁC NHẬN ĐỔI VÉ THÀNH CÔNG</h2>

    <div class="alert alert-success mt-4">
        <i class="fas fa-check-circle"></i> <strong>Giao dịch đổi vé đã hoàn tất!</strong><br>
        <p>Chúng tôi đã nhận yêu cầu đổi vé của bạn. Hệ thống đã xử lý và cập nhật thông tin vé mới. Quý khách có thể kiểm tra email hoặc thông tin tài khoản.</p>
        <p><strong>Lưu ý:</strong> Nếu có thắc mắc, vui lòng liên hệ bộ phận hỗ trợ khách hàng.</p>
    </div>

    <!-- Exchange Transaction Details -->
    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title">Thông tin giao dịch đổi vé</h5>
            <p><strong>Mã đặt chỗ cũ:</strong> {{ $exchange->old_ticket->booking_id }}</p>
            <p><strong>Mã đặt chỗ mới:</strong> {{ $exchange->new_ticket->booking_id }}</p>
            <p><strong>Ngày đổi vé:</strong> {{ $exchange->exchange_time }}</p>
            <p><strong>Phương thức thanh toán:</strong> {{ $exchange->payment_method }}</p>
            <p><strong>Chênh lệch giá:</strong> {{ number_format($exchange->price_difference) }} VNĐ</p>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="mt-4 text-center">
        <a href="/" class="btn btn-primary btn-lg">Quay về trang chủ</a>
        <a href="{{ route('exchange.showTransactionDetails', ['exchange_id' => $exchange->id]) }}" class="btn btn-link btn-lg">Xem chi tiết giao dịch</a>
    </div>
</div>

<style>
    /* Custom Styles */
    .alert-success {
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
        padding: 15px;
        border-radius: 5px;
    }

    .alert-success i {
        margin-right: 10px;
    }

    .card {
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .card-body {
        padding: 20px;
    }

    .card-title {
        font-size: 1.25rem;
        margin-bottom: 15px;
    }

    .btn-link {
        text-decoration: none;
        color: #007bff;
    }
</style>
@endsection
