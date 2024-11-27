@extends('layouts.app')
@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between mb-4">
            <div class="step text-center">
                <span class="badge badge-primary">1</span>
                <p>Chọn vé cần trả</p>
            </div>
            <div class="step text-center">
                <span class="badge badge-secondary">2</span>
                <p>Xác nhận</p>
            </div>
            <div class="step text-center">
                <span class="badge badge-secondary">3</span>
                <p>Hoàn tất</p>
            </div>
        </div>

        <h2 class="text-primary">CHỌN VÉ CẦN TRẢ</h2>

        <!-- Thông tin Booking -->
        <div class="mb-4">
            <table class="table table-bordered">
                <tr>
                    <th>Mã đặt vé</th>
                    <td>{{ $booking->id }}</>
                    </td>
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
        <h4 class="mb-3">Các giao dịch thành công:</h4>
        @if (!$tickets)
            <div class="alert alert-warning">
                Không có vé nào để trả cho mã đặt chỗ này.
            </div>
        @else
            <form action="{{ route('refund.createRefund') }}" method="POST">
                @csrf
                <input type="hidden" name="booking_id" value="{{ $booking->id }}">

                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Họ tên</th>
                            <th>Thông tin vé</th>
                            <th>Thành tiền (VND)</th>
                            <th>Lệ phí trả vé</th>
                            <th>Tiền trả lại</th>
                            <th>Trạng thái</th>
                            <th>Chọn vé trả</th>
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
                                    <p>{{ $ticket?->schedule?->car_name }} số {{ $ticket?->schedule?->seat_number }}</p>
                                </td>
                                <td>{{ number_format($ticket->price * (1 - $ticket->discount_price), 0, ',', '.') }}</td>
                                <td>{{ number_format($ticket->price * 0.2, 0, ',', '.') }}</td>
                                <td>{{ number_format($ticket->price * (1 - $ticket->discount_price - 0.2), 0, ',', '.') }}</td>
                                <td>
                                    @if ($ticket->refund)
                                        @if ($ticket->refund?->refund_status === 'completed')
                                            Đã trả vé
                                        @elseif ($ticket->refund?->refund_status === 'confirmed')
                                            Đã xác nhận trả vé
                                        @elseif ($ticket->refund?->refund_status === 'pending')
                                            Đang chờ xử lý
                                        @elseif ($ticket->refund?->refund_status === 'rejected')
                                            Đã hủy trả vé
                                        @endif
                                    @else
                                        Chưa có hoàn vé
                                    @endif
                                </td>
                                <td>
                                    <input type="checkbox" name="ticket_array[]" value="{{ $ticket->id }}"
                                        id="ticket_{{ $ticket->id }}" class="ticket-checkbox"
                                        @if ($ticket->refund?->refund_status === 'completed' || $ticket->refund?->refund_status === 'confirmed') disabled @endif>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4 text-center">
                    <button type="submit" class="btn btn-primary" id="submit-button" disabled>Gửi yêu cầu hoàn vé</button>
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

        th, td {
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
            const checkboxes = document.querySelectorAll('.ticket-checkbox');
            const submitButton = document.getElementById('submit-button');

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const isAnyChecked = Array.from(checkboxes).some(cb => cb.checked);
                    submitButton.disabled = !isAnyChecked;
                });
            });
        });
    </script>
@endsection
