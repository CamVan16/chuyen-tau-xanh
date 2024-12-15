@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <!-- Progress Steps -->
        <div class="d-flex justify-content-between mb-4">
            <div class="step text-center">
                <span class="badge badge-primary">1</span>
                <p class="text-primary">Chọn vé đổi</p>
            </div>
            <div class="step text-center">
                <span class="badge badge-secondary">2</span>
                <p>Tìm vé đổi</p>
            </div>
            <div class="step text-center">
                <span class="badge badge-secondary">3</span>
                <p>Xác nhận</p>
            </div>
            <div class="step text-center">
                <span class="badge badge-secondary">4</span>
                <p>Hoàn tất</p>
            </div>
        </div>

        <!-- Title -->
        <h2 class="text-primary text-center mb-4">ĐỔI VÉ TRỰC TUYẾN</h2>

        <!-- Instructions -->
        <div class="alert alert-info">
            <p>
                Quý khách chỉ có thể đổi vé đã thanh toán trực tuyến qua cổng thanh toán, ví điện tử hoặc app ngân hàng.
                Nếu thanh toán bằng tiền mặt hoặc gặp sự cố bãi bỏ tàu, vui lòng thực hiện đổi vé tại nhà ga hoặc đại lý bán
                vé.
            </p>
        </div>

        <!-- Form -->
        <form action="{{ route('exchange.findBooking') }}" method="GET">
            @csrf
            <div class="card shadow-sm p-4">
                <p><strong>Vui lòng nhập chính xác các thông tin dưới đây để tìm vé cần đổi:</strong></p>
                <!-- Booking Code -->
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
                    <a href="{{ route('exchange.showBookingCodeForm') }}" class="btn btn-link">Quên mã đặt chỗ?</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Styling -->
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
