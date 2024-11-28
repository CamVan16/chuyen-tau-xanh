@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <!-- Bước tiến trình -->
        <div class="d-flex justify-content-between mb-4">
            <div class="step text-center">
                <span class="badge badge-primary">1</span>
                <p>Chọn vé cần đổi</p>
            </div>
            <div class="step text-center">
                <span class="badge badge-secondary">2</span>
                <p>Tìm vé đổi</p>
            </div>
            <div class="step text-center">
                <span class="badge badge-secondary">3</span>
                <p>Xác nhận</p>
            </div>
            <div class="step text-center">
                <span class="badge badge-secondary">4</span>
                <p>Hoàn tất</p>
            </div>
        </div>

        <h2 class="text-primary mb-4">CHỌN VÉ CẦN ĐỔI</h2>

        <!-- Thông tin Booking -->
        <div class="mb-4">
            <h4>Thông tin đặt vé</h4>
            <table class="table table-bordered">
                <tr>
                    <th>Mã đặt vé</th>
                    <td>{{ $booking->id }}</td>
                </tr>
                <tr>
                    <th>Họ tên</th>
                    <td>{{ $booking->customer->customer_name }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $booking->customer->email }}</td>
                </tr>
                <tr>
                    <th>Số điện thoại</th>
                    <td>{{ $booking->customer->phone }}</td>
                </tr>
            </table>
        </div>

        <!-- Danh sách vé -->
        <h4 class="mb-3">Danh sách vé:</h4>
        @if ($tickets->isEmpty())
            <div class="alert alert-warning">
                Không có vé nào để đổi cho mã đặt chỗ này.
            </div>
        @else
            <form action="{{ route('exchange.findTicket') }}" method="POST">
                @csrf
                <input type="hidden" name="booking_id" value="{{ $booking->id }}">

                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Họ tên</th>
                            <th>Thông tin vé</th>
                            <th>Thành tiền</th>
                            <th>Lệ phí đổi vé</th>
                            <th>Tiền trả lại</th>
                            <th>Trạng thái</th>
                            <th>Chọn vé đổi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tickets as $index => $ticket)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="text-start">
                                    <p>{{ $ticket?->customer?->customer_name }}</p>
                                    <p>Đối tượng: {{ $ticket?->customer?->customer_type }}</p>
                                    <p>Số giấy tờ: {{ $ticket?->customer?->citizen_id }}</p>
                                </td>
                                <td class="text-start">
                                    <p><strong>Mã vé:</strong> {{ $ticket?->id }}</p>
                                    <p><strong>Mã tàu:</strong> {{ $ticket?->schedule?->train_mark }}</p>
                                    <p>{{ $ticket?->schedule?->day_start }} {{ $ticket?->schedule?->time_start }}</p>
                                    <p><strong>Toa:</strong> {{ $ticket?->schedule?->car_name }} - Ghế:
                                        {{ $ticket?->schedule?->seat_number }}</p>
                                </td>
                                <td>{{ number_format($ticket->price * (1 - $ticket->discount_price), 0, ',', '.') }}</td>
                                <td>{{ number_format($ticket->price * 0.1, 0, ',', '.') }}</td>
                                <td>{{ number_format($ticket->price * (1 - $ticket->discount_price - 0.1), 0, ',', '.') }}
                                </td>
                                <td>
                                    @if ($ticket->refund)
                                        @switch($ticket->refund->refund_status)
                                            @case('completed')
                                                <span class="badge badge-success">Đã trả vé</span>
                                            @break

                                            @case('confirmed')
                                                <span class="badge badge-info">Đã xác nhận trả vé</span>
                                            @break

                                            @case('pending')
                                                <span class="badge badge-warning">Đang chờ xử lý</span>
                                            @break

                                            @case('rejected')
                                                <span class="badge badge-danger">Đã hủy trả vé</span>
                                            @break
                                        @endswitch
                                    @elseif ($ticket->exchange)
                                        @switch($ticket->exchange->exchange_status)
                                            @case('completed')
                                                <span class="badge badge-success">Đã đổi vé</span>
                                            @break

                                            @case('confirmed')
                                                <span class="badge badge-info">Đã xác nhận đổi vé</span>
                                            @break

                                            @case('pending')
                                                <span class="badge badge-warning">Đang chờ xử lý</span>
                                            @break

                                            @case('rejected')
                                                <span class="badge badge-danger">Đã hủy đổi vé</span>
                                            @break
                                        @endswitch
                                    @else
                                        <span class="badge badge-secondary">Chưa có vé đổi</span>
                                    @endif
                                </td>
                                <td>
                                    <input type="radio" name="ticket_array" value="{{ $ticket->id }}"
                                        id="ticket_{{ $ticket->id }}" class="ticket-radio"
                                        @if ($ticket->exchange?->exchange_status === 'completed' || $ticket->exchange?->exchange_status === 'confirmed') disabled
                                    @elseif ($ticket->refund?->refund_status === 'completed' || $ticket->refund?->refund_status === 'confirmed')
                                        disabled @endif />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4 text-center">
                    <button type="submit" class="btn btn-primary" id="submit-button" disabled>Chọn vé để đổi</button>
                </div>
            </form>
        @endif
    </div>

    <style>
        .step {
            flex: 1;
        }

        .step .badge {
            font-size: 1.2em;
            padding: 10px;
        }

        .step p {
            margin-top: 10px;
        }

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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const radios = document.querySelectorAll('.ticket-radio');
            const submitButton = document.getElementById('submit-button');

            radios.forEach(radio => {
                radio.addEventListener('change', function() {
                    const isAnyChecked = Array.from(radios).some(r => r.checked);
                    submitButton.disabled = !isAnyChecked;
                });
            });
        });
    </script>
@endsection
