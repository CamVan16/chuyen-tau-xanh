@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="text-primary mb-4">Tìm vé để đổi</h2>

        <!-- Hiển thị thông tin của $old_ticket -->
        @if ($old_ticket)
            <div class="alert alert-info">
                <strong>Thông tin vé cũ:</strong>
                <p>Mã vé: {{ $old_ticket->id }}</p>
                <p>Ngày khởi hành: {{ $old_ticket->schedule?->day_start }} {{ $old_ticket->schedule?->time_start }}</p>
                <p>Giá vé: {{ $old_ticket->price }}</p>
            </div>
        @else
            <div class="alert alert-warning">
                Không có vé cũ để đổi.
            </div>
        @endif

        <!-- Nếu không có vé nào để đổi -->
        @if ($tickets->isEmpty())
            <div class="alert alert-warning">
                Không có vé nào có thể đổi tại thời điểm này.
            </div>
        @else
            <!-- Danh sách vé chưa được chọn -->
            <h4 class="mb-3">Chọn vé để đổi:</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Mã vé</th>
                        <th>Ngày khởi hành</th>
                        <th>Giá vé</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tickets as $ticket)
                        <tr>
                            <td>{{ $ticket->id }}</td>
                            <td>{{ $ticket?->schedule?->day_start }} {{ $ticket?->schedule?->time_start }}</td>
                            <td>{{ $ticket?->price }}</td>
                            <td>
                                <form action="{{ route('exchange.createExchange') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="booking_id" value="{{ $old_ticket?->booking->id }}">
                                    <input type="hidden" name="old_ticket_id" value="{{ $old_ticket?->id ?? '' }}">
                                    <input type="hidden" name="payment_method" value="ATM">
                                    <input type="hidden" name="new_ticket_id" value="{{ $ticket->id }}">
                                    <button type="submit" class="btn btn-success">Chọn vé này</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
