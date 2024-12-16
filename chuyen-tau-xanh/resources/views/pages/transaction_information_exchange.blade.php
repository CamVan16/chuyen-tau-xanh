@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <!-- Title -->
    <h3 class="text-center mb-4">Thông tin giao dịch đổi vé</h3>

    <!-- Transaction Status -->
    <div class="alert alert-info">
        <h5>Kết quả giao dịch:
            @if ($status === 'success')
                <span class="text-success">Thành công</span>
            @else
                <span class="text-danger">Thất bại</span>
            @endif
        </h5>
    </div>

    <!-- Old Ticket Info -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">Thông tin vé cũ</div>
        <div class="card-body">
            <ul class="list-unstyled">
                <li><strong>Mã vé cũ:</strong> {{ $newExchange->old_ticket_id ?? 'Không có dữ liệu' }}</li>
                <li><strong>Giá vé cũ:</strong> {{ number_format($newExchange->old_price ?? 0, 0, ',', '.') }} VND</li>
            </ul>
        </div>
    </div>

    <!-- New Ticket Info -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">Thông tin vé mới</div>
        <div class="card-body">
            <ul class="list-unstyled">
                <li><strong>Mã đặt vé mới:</strong> {{ $newExchange->newBookingId ?? 'Không có dữ liệu' }}</li>
                <li><strong>Mã vé mới:</strong> {{ $newExchange->new_ticket_id ?? 'Không có dữ liệu' }}</li>
                <li><strong>Giá vé mới:</strong> {{ number_format($newExchange->new_price ?? 0, 0, ',', '.') }} VND</li>
            </ul>
        </div>
    </div>

    <!-- Payment Info -->
    <div class="mt-4">
        <h3 class="text-center mb-3">Thông tin thanh toán</h3>
        <p><strong>Phương thức thanh toán:</strong> {{ $payment ?? 'Không có thông tin' }}</p>
        <p><strong>Tiền phải trả:</strong> {{ number_format($newExchange->additional_price ?? 0, 0, ',', '.') }} VND</p>

        @if ($status === 'success')
            <div class="alert alert-success">
                <strong>Giao dịch thành công!</strong>
            </div>
        @else
            <div class="alert alert-danger">
                <strong>Giao dịch thất bại!</strong>
            </div>
        @endif
    </div>
</div>
@endsection
