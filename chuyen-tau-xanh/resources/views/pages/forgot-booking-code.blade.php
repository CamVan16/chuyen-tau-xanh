@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2>Quên Mã Đặt Chỗ</h2>
        <p>Nhập email của bạn để nhận mã đặt chỗ qua email.</p>
        <form action="{{ route('booking.forgot.process') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
            </div>

            @if (session('success'))
                <div class="alert alert-success mt-2">{{ session('success') }}</div>
            @endif

            @if ($errors->has('error'))
                <div class="alert alert-danger mt-2">{{ $errors->first('error') }}</div>
            @endif
            <button type="submit" class="btn btn-primary mt-3">Gửi mã</button>
            <a href="{{ route('booking.lookup.form') }}" class="btn btn-secondary mt-3">Quay lại</a>
        </form>
    </div>
@endsection
