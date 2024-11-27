@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between mb-4">
        <div class="step text-center">
            <span class="badge badge-primary">1</span>
            <p>Chọn vé trả</p>
        </div>
        <div class="step text-center">
            <span class="badge badge-primary">2</span>
            <p>Xác nhận</p>
        </div>
        <div class="step text-center">
            <span class="badge badge-primary">3</span>
            <p>Hoàn tất</p>
        </div>
    </div>

    <h2 class="text-primary">XÁC NHẬN TRẢ VÉ THÀNH CÔNG</h2>

    <div class="alert alert-success mt-4">
        <i class="fas fa-check-circle"></i> <strong>Giao dịch trả vé đã hoàn tất!</strong><br>
        <p>Chúng tôi đã nhận yêu cầu trả vé của bạn. Hệ thống sẽ xử lý hoàn tiền theo quy định. Quý khách sẽ nhận được thông báo qua email.</p>
        <p><strong>Lưu ý:</strong> Số tiền hoàn lại sẽ được chuyển vào tài khoản của quý khách trong vòng 3 ngày làm việc.</p>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title">Thông tin giao dịch</h5>
            <p><strong>Mã đặt chỗ:</strong> {{ $refund->booking_id }} </p>
            <p><strong>Ngày trả vé:</strong> {{ $refund->refund_date }}</p>
            <p><strong>Số tiền hoàn:</strong> {{ number_format($refund->refund_amount) }} VNĐ </p>
        </div>
    </div>

    <div class="mt-4">
        <a href="/" class="btn btn-primary">Quay về trang chủ</a>
        <a href="{{ route('refund.showTransactionDetails', ['refund_id' => $refund->id]) }}" class="btn btn-link">Xem chi tiết giao dịch</a>
    </div>
</div>

<style>
    .step {
        flex: 1;
    }
    .step .badge {
        font-size: 1.2em;
        padding: 10px;
    }
    .step p {
        margin-top: 10px;
    }
    .card {
        border: 1px solid #ddd;
        border-radius: 5px;
    }
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
</style>
@endsection
