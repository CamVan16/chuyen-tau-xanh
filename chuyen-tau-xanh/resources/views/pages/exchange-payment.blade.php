@extends('layouts.app')

@section('title', 'Đặt chỗ')
@section('head')
    @parent
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <div class="step" id="step-3" style="display:none;">
        <h3>Thanh toán</h3>
        <form action="{{ route('booking.processPayment') }}" method="POST" class="mt-4">
            @csrf

            <!-- Booking ID (Order ID) -->
            <div class="mb-3">
                <label for="order_id" class="form-label">Booking ID (Order ID):</label>
                <input readonly type="text" name="order_id" id="order_id" class="form-control"
                    value="{{ old('order_id') }}">
            </div>

            <!-- Total Price -->
            <div class="mb-3">
                <label for="amount" class="form-label">Tổng tiền (Total Price):</label>
                <input readonly type="number" name="amount" id="amount" class="form-control"
                    value="{{ old('amount') }}">
            </div>

            <!-- Order Description -->
            <div class="mb-3">
                <label for="order_desc" class="form-label">Mô tả (Order Description):</label>
                <input type="text" name="order_desc" id="order_desc" class="form-control" value="Thanh toán vé tàu"
                    required>
            </div>

            <!-- Order Type -->
            <div class="mb-3">
                <label for="order_type" class="form-label">Loại đơn hàng (Order Type):</label>
                <input type="text" name="order_type" id="order_type" class="form-control" value="billpayment" required>
            </div>

            <!-- Language Selection -->
            <div class="mb-3">
                <label for="language" class="form-label">Ngôn ngữ (Language):</label>
                <select name="language" id="language" class="form-select">
                    <option value="vn">Tiếng Việt</option>
                    <option value="en">English</option>
                </select>
            </div>

            <!-- Bank Selection -->
            <div class="mb-3">
                <label for="bank_code" class="form-label">Ngân hàng (Bank Code):</label>
                <select name="bank_code" id="bank_code" class="form-select">
                    <option value="">Không chọn</option>
                    <option value="NCB">Ngân hàng NCB</option>
                    <option value="VCB">Ngân hàng Vietcombank</option>
                </select>
            </div>

            <!-- Payment Method -->
            <div class="mb-3">
                <label for="payment_method" class="form-label">Phương thức thanh toán (Payment Method):</label>
                <select name="payment_method" id="payment_method" class="form-select" required>
                    <option value="vnpay">VNPay</option>
                    <option value="zalopay">ZaloPay</option>
                    <option value="momo">Momo</option>
                </select>
            </div>

            <!-- Hidden Field to Store Booking Info -->
            <input type="hidden" name="booking-info" id="booking-info" value="{{ old('booking-info') }}">

            <!-- Buttons -->
            <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-secondary back-btn" data-prev="step-2">Quay lại</button>
                <button type="submit" class="btn btn-primary">Thanh Toán</button>
            </div>
        </form>
    </div>

@endsection
