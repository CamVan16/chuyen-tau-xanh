@extends('layouts.app')

@section('content')
<div class="container">
    <h3>TRA CỨU THÔNG TIN ĐẶT CHỖ</h3>
    <p>Để tra cứu thông tin, quý khách vui lòng nhập chính xác 3 thông tin bên dưới.</p>
    <form action="{{ route('booking.lookup.process') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="booking_id">Mã đặt chỗ <span class="text-danger">*</span></label>
            <input type="text" name="booking_id" id="booking_id" class="form-control" placeholder="Mã đặt chỗ khi đặt vé" required>
        </div>
        <div class="form-group">
            <label for="email">Email <span class="text-danger">*</span></label>
            <input type="email" name="email" id="email" class="form-control" placeholder="Địa chỉ email khi đặt vé" required>
        </div>
        <div class="form-group">
            <label for="phone">Điện thoại <span class="text-danger">*</span></label>
            <input type="text" name="phone" id="phone" class="form-control" placeholder="Số điện thoại khi đặt vé" required>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Tra cứu</button>
        <a href="{{ route('booking.forgot') }}" class="btn btn-secondary mt-3">Quên mã đặt chỗ?</a>
    </form>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
</div>
@endsection
