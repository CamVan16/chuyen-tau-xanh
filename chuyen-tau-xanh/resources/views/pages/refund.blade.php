@extends('layouts.app')


@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between mb-4">
        <div class="step text-center">
            <span class="badge badge-primary">1</span>
            <p>Chọn vé trả</p>
        </div>
        <div class="step text-center">
            <span class="badge badge-secondary">2</span>
            <p>Xác nhận</p>
        </div>
        <div class="step text-center">
            <span class="badge badge-secondary">3</span>
            <p>Hoàn tất</p>
        </div>
    </div>


    <h2 class="text-primary">TRẢ VÉ TRỰC TUYẾN</h2>


    <div class="alert alert-info">
        <p>Trả vé trực tuyến chỉ áp dụng với trường hợp khách hàng đã thanh toán trực tuyến (qua cổng thanh toán, ví điện tử, app ngân hàng) và có điền email khi mua vé.
        Nếu quý khách thanh toán bằng tiền mặt, trả sau qua ứng dụng ngân hàng và ATM, chuyển khoản hoặc trả vé khi có sự cố bãi bỏ tàu vui lòng thực hiện thủ tục tại các nhà ga, đại lý bán vé.</p>
    </div>

    <form action="{{ route('refund.findBooking') }}" method="GET">
        @csrf
        <p><strong>Để hiển thị các vé cần trả, vui lòng điền chính xác 3 thông tin dưới đây:</strong></p>

        <!-- Mã đặt chỗ -->
        <div class="form-group">
            <label for="booking_id">Mã đặt chỗ</label>
            <input type="text" class="form-control" id="booking_id" name="booking_id" value="{{ old('booking_id') }}" required>
        </div>

        <!-- Email -->
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
        </div>

        <!-- Số điện thoại -->
        <div class="form-group">
            <label for="phone">Điện thoại</label>
            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" required>
        </div>
        @error('error')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
        @enderror
        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">Tra cứu</button>
            <a href={{route('refund.showBookingCodeForm')}} class="btn btn-link">Quên mã đặt chỗ?</a>
        </div>
    </form>
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
</style>
@endsection
