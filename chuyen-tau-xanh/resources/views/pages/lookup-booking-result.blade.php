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

        {{-- Hiển thị danh sách vé --}}
        <div class="card shadow-sm p-4 mt-4">
            <h4 class="text-primary mb-3">Danh sách vé</h4>
            @if ($booking->tickets->isNotEmpty())
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Mã vé</th>
                            <th>Tên hành khách</th>
                            <th>Email</th>
                            <th>Điện thoại</th>
                            <th>Giá vé</th>
                            <th>Trạng thái vé</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($booking->tickets as $ticket)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $ticket->id }}</td>
                                <td>{{ $ticket->customer->customer_name ?? 'Không xác định' }}</td>
                                <td>{{ $ticket->customer->email ?? 'Không xác định' }}</td>
                                <td>{{ $ticket->customer->phone ?? 'Không xác định' }}</td>
                                <td>{{ number_format($ticket->price) }} VNĐ</td>
                                <td>
                                    @if ($ticket->ticket_status == 1)
                                        <span class="text-success">Có hiệu lực</span>
                                    @else
                                        <span class="text-danger">Hết hiệu lực</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-danger">Không có vé nào được tìm thấy.</p>
            @endif
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('booking.lookup.form') }}" class="btn btn-primary px-4">Quay lại</a>
        </div>
    </div>
@endsection
