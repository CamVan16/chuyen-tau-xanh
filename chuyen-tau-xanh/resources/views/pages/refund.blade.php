@extends('layouts.app')


@section('content')
    <div class="container mt-4">
        <!-- Progress Steps -->
        <div class="d-flex justify-content-between mb-4">
            <div class="step text-center">
                <span class="badge badge-primary">1</span>
                <p class="text-primary">Chọn vé trả</p>
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

        <h2 class="text-primary text-center mb-4">TRẢ VÉ TRỰC TUYẾN</h2>

        <div class="alert alert-info">
            <p>Trả vé trực tuyến chỉ áp dụng với trường hợp khách hàng đã thanh toán trực tuyến (qua cổng thanh toán, ví
                điện tử, app ngân hàng) và có điền email khi mua vé.
                Nếu quý khách thanh toán bằng tiền mặt, trả sau qua ứng dụng ngân hàng và ATM, chuyển khoản hoặc trả vé khi
                có sự cố bãi bỏ tàu vui lòng thực hiện thủ tục tại các nhà ga, đại lý bán vé.</p>
        </div>

        <form action="{{ route('refund.findBooking') }}" method="GET">
            @csrf
            <div class="card shadow-sm p-4">
                <p><strong>Vui lòng nhập chính xác các thông tin dưới đây để tìm vé cần trả:</strong></p>
                <div class="form-group">
                    <label for="booking_id">Mã đặt chỗ <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="booking_id" name="booking_id"
                        value="{{ old('booking_id') }}" placeholder="Nhập mã đặt chỗ của bạn" required>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="email">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}"
                        placeholder="Nhập email bạn đã dùng khi đặt vé" required>
                </div>

                <!-- Phone -->
                <div class="form-group">
                    <label for="phone">Số điện thoại <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}"
                        placeholder="Nhập số điện thoại liên hệ" required>
                </div>

                @error('error')
                    <div class="alert alert-danger mt-2">
                        {{ $message }}
                    </div>
                @enderror

                <!-- Buttons -->
                <div class="d-flex justify-content-between mt-4">
                    <button type="submit" class="btn btn-primary px-4">Tra cứu vé</button>
                    <a href="{{ route('refund.showBookingCodeForm') }}" class="btn btn-link">Quên mã đặt chỗ?</a>
                </div>
            </div>
        </form>
    </div>

    <style>
        .step {
            flex: 1;
        }

        .step .badge {
            font-size: 1.4em;
            padding: 12px;
        }

        .step p {
            margin-top: 10px;
        }

        .card {
            border: none;
            border-radius: 10px;
        }

        .card .form-control {
            border-radius: 5px;
        }
    </style>
@endsection
