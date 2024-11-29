@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Nhập Email Để Lấy Mã Đặt Chỗ</h2>

    <form action="{{ route('refund.sendBookingCode') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
        </div>

        @if(session('success'))
            <div class="alert alert-success mt-2">{{ session('success') }}</div>
        @endif

        @if($errors->has('error'))
            <div class="alert alert-danger mt-2">{{ $errors->first('error') }}</div>
        @endif

        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">Gửi mã</button>
            <a href="/tra-ve" class="btn btn-link">Quay lại</a>
        </div>
    </form>
</div>
@endsection
