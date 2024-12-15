@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="text-primary text-center mb-4">TRA CỨU THÔNG TIN ĐẶT CHỖ</h2>

        <div class="alert alert-info">
            <p>Để tra cứu thông tin, quý khách vui lòng nhập chính xác 3 thông tin bên dưới. Nếu có bất kỳ vấn đề gì, vui
                lòng liên hệ với chúng tôi.</p>
        </div>

        <div class="card shadow-sm p-4">
            <form action="{{ route('booking.lookup.process') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="booking_id">Mã đặt chỗ <span class="text-danger">*</span></label>
                    <input type="text" name="booking_id" id="booking_id" class="form-control"
                        placeholder="Mã đặt chỗ khi đặt vé" required>
                </div>
                <div class="form-group">
                    <label for="email">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" id="email" class="form-control"
                        placeholder="Địa chỉ email khi đặt vé" required>
                </div>
                <div class="form-group">
                    <label for="phone">Điện thoại <span class="text-danger">*</span></label>
                    <input type="text" name="phone" id="phone" class="form-control"
                        placeholder="Số điện thoại khi đặt vé" required>
                </div>
                <div class="d-flex justify-content-between mt-4">
                    <button type="submit" class="btn btn-primary px-4">Tra cứu</button>
                    <a href="{{ route('booking.forgot') }}" class="btn btn-link">Quên mã đặt chỗ?</a>
                </div>
            </form>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger mt-4">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
@endsection
