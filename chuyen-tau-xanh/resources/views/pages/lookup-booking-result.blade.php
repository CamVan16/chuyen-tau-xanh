@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="text-primary text-center mb-4">THÔNG TIN ĐẶT CHỖ</h2>

        <div class="alert alert-info">
            <p>Thông tin chi tiết đặt chỗ của bạn. Nếu có bất kỳ vấn đề gì, vui lòng liên hệ với chúng tôi để được hỗ trợ.
            </p>
        </div>

        <div class="card shadow-sm p-4">
            <table class="table table-striped table-hover">
                <tbody>
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
                </tbody>
            </table>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('booking.lookup.form') }}" class="btn btn-primary px-4">Quay lại</a>
        </div>
    </div>
@endsection
