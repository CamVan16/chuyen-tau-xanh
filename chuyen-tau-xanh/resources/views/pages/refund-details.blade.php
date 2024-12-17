@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="text-primary">CHI TIẾT GIAO DỊCH HOÀN TIỀN</h2>

        <div class="mb-4">
            <table class="table table-bordered">
                <tr>
                    <th>Mã đặt chỗ</th>
                    <td>{{ $refund->booking_id }}</>
                    </td>
                </tr>
                <tr>
                    <th>Ngày trả vé</th>
                    <td>{{ $refund->refund_time }}</td>
                </tr>
                <tr>
                    <th>Số tiền hoàn</th>
                    <td>{{ number_format($refund->refund_amount) }} VNĐ</td>
                </tr>
            </table>
        </div>

        <h4 class="mt-4">Danh sách vé đã hủy:</h4>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th class="text-start">Họ tên</th>
                    <th>Thông tin vé</th>
                    <th>Thành tiền (VND)</th>
                    <th>Lệ phí trả vé</th>
                    <th>Tiền trả lại</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tickets as $index => $ticket)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="text-start">
                            <p>{{ $ticket->customer->customer_name }}</p>
                            <p>{{ $ticket->customer->customer_type }}</p>
                            <p>Số giấy tờ: {{ $ticket->customer->citizen_id }}</p>
                        </td>
                        <td class="text-start">
                            <p>Mã vé: {{ $ticket->id }}</p>
                            <p>Mã tàu: {{ $ticket?->schedule?->train_mark }}</p>
                            <p>{{ $ticket?->schedule?->day_start }} {{ $ticket?->schedule?->time_start }}</p>
                            <p>Toa {{ $ticket?->schedule?->car_name }} | Ghế {{ $ticket?->schedule?->seat_number }}</p>
                        </td>
                        <td>{{ number_format($ticket->price - $ticket->discount_price), 0, ',', '.' }}</td>
                        <td>{{ number_format($ticket->refund_fee * $ticket->price, 0, ',', '.') }}</td>
                        <td>{{ number_format($ticket->price * (1 - $ticket->refund_fee)  - $ticket->discount_price, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <a href="{{ route('refund.success', ['refund_id' => $refund->id]) }}" class="btn btn-primary mt-4">Quay lại</a>
    </div>
    <style>
        th,
        td {
            text-align: center;
        }

        .text-start {
            text-align: start;
        }

        p {
            margin: 0;
        }
    </style>
@endsection
