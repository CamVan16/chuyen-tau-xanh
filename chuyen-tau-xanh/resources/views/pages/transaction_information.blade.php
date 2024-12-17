@extends('layouts.app')

@section('content')
<div>
    <h1 class="text-center text-primary">THÔNG TIN GIAO DỊCH</h1>

    <div class="transaction-result">
        @if ($status !== 'success')
            <h3 style="color: red;" class="text-center">Thanh toán không thành công</h3>
            <span class="text-center">Đã có lỗi xảy ra. Vui lòng thử lại!</span>
        @else
            <div class="booker-info">
                <h3>Thông tin người đặt</h3>
                <p><strong>Họ tên:</strong> {{ $booking['booker']['name'] }}</p>
                <p><strong>Email:</strong> {{ $booking['booker']['email'] ?: 'Không có' }}</p>
                <p><strong>Số định danh:</strong> {{ $booking['booker']['citizen'] }}</p>
                <p><strong>Số điện thoại:</strong> {{ $booking['booker']['phone'] }}</p>
            </div>

            <div class="ticket-info">
                <h3>Thông tin vé</h3>
                <table border="1" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th>Thông tin hành khách</th>
                            <th>Thông tin vé</th>
                            <th>Thành tiền</th>
                            <th>Tình trạng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($booking['tickets'] as $ticket)
                            <tr>
                                <td>
                                    Họ tên:{{ $ticket['passenger_name'] }} <br>
                                    Đối tượng: {{ $ticket['customer_type'] }} <br>
                                    Số định danh: {{ $ticket['passenger_citizen'] }} <br>
                                </td>
                                <td>
                                    {{ $ticket['train_mark'] }} <br>
                                    Khởi hành: {{ $ticket['day_start'] }} {{ $ticket['time_start'] }} ({{ $ticket['station_start'] }}) <br>
                                    Toa: {{ $ticket['car_name'] }}, Ghế số: {{ $ticket['seat_number'] }} <br>
                                </td>
                                <td>{{ number_format($ticket['ticket_price'], 0, ',', '.') }} VND</td>
                                <td>Đã thanh toán</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>
@endsection
