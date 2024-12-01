@extends('layouts.app')

@section('content')
<div class="container">
    <h3>THÔNG TIN ĐẶT CHỖ</h3>
    <table class="table table-bordered">
        <tr>
            <th>Mã đặt chỗ</th>
            <td>{{ $booking->id }}</td>
        </tr>
        <tr>
            <th>Khách hàng</th>
            <td>{{ $booking->customer->customer_name }}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>{{ $booking->customer->email }}</td>
        </tr>
        <tr>
            <th>Điện thoại</th>
            <td>{{ $booking->customer->phone }}</td>
        </tr>
        <tr>
            <th>Tổng giá</th>
            <td>{{ number_format($booking->total_price) }} VNĐ</td>
        </tr>
        <tr>
            <th>Thời gian đặt</th>
            <td>{{ $booking->booked_time }}</td>
        </tr>
        <tr>
            <th>Trạng thái</th>
            <td>{{ $booking->booking_status }}</td>
        </tr>
    </table>
    <a href="{{ route('booking.lookup.form') }}" class="btn btn-secondary">Quay lại</a>
</div>
@endsection
