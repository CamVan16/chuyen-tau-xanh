@extends('layouts.app')

@section('content')
<script>
    $(document).ready(function () {
    // var bookingData = $('#booking-data').data('booking');

    // var booking = JSON.parse(bookingData);

    // console.log("Booking Data:", booking);

    });
</script>
    <div>
        <h1>Transaction Information</h1>

        <!-- <div class="transaction-status">
            @if ($status === 'success')
                <h2 style="color: green;">Transaction Successful</h2>
                <p>Thank you for your payment!</p>
            @else
                <h2 style="color: red;">Transaction Failed</h2>
                <p>Unfortunately, your payment was unsuccessful. Please try again!</p>
            @endif
        </div>
        <div class="payment-method">
            <h3>Payment Method: {{ ucfirst($payment) }}</h3>
        </div>

        @if (!empty($booking))
        <div class="booking-info">
            <h3>Booking Information</h3>

            {{-- Hiển thị thông tin người đặt (booker) --}}
            <div class="booker-info">
                <h4>Booker Information</h4>
                <p><strong>Name:</strong> {{ $booking['booker']['name'] }}</p>
                <p><strong>Email:</strong> {{ $booking['booker']['email'] }}</p>
                <p><strong>Phone:</strong> {{ $booking['booker']['phone'] }}</p>
            </div>

            {{-- Hiển thị danh sách vé (tickets) --}}
            <div id="booking-data" data-booking="{{ htmlspecialchars(json_encode($booking)) }}" class="ticket-info">
                <h4>Tickets</h4>
                <table border="1" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th>Seat</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($booking['tickets'] as $ticket)
                            <tr>
                                <td>{{ $ticket['seat'] }}</td>
                                <td>{{ number_format($ticket['price'], 0, ',', '.') }} VND</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Hiển thị tổng số tiền (amount) --}}
            <div class="amount-info">
                <h4>Total Amount</h4>
                <p><strong>{{ number_format($booking['amount'], 0, ',', '.') }} VND</strong></p>
            </div>
        </div>

        @else
            <p>No booking information found.</p>
        @endif -->
    </div>
</body>
</html>


@endsection 

