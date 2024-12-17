@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="text-primary text-center mb-4">LẤY LẠI MÃ ĐẶT CHỖ</h2>

        <div class="alert alert-info">
            <p>Nhập email của bạn để nhận mã đặt chỗ qua email. Nếu bạn gặp khó khăn, vui lòng liên hệ với chúng
                tôi để được hỗ trợ.</p>
        </div>

        <div class="card shadow-sm p-4">
            <form action="{{ route('refund.sendBookingCode') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="email">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}"
                        required placeholder="Nhập email đã đăng ký">
                </div>

                @if (session('success'))
                    <div class="alert alert-success mt-2">{{ session('success') }}</div>
                @endif

                @if ($errors->has('error'))
                    <div class="alert alert-danger mt-2">{{ $errors->first('error') }}</div>
                @endif

                <div class="d-flex justify-content-between mt-3">
                    <button type="submit" class="btn btn-primary">Gửi mã</button>
                    <a href="/tra-ve" class="btn btn-link">Quay lại</a>
                </div>
            </form>
        </div>
    </div>
@endsection
