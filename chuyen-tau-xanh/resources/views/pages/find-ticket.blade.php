@extends('layouts.app')

@section('title', 'Tìm vé')

@section('content')
<div>
    <form action="{{ route('routes.search') }}" method="POST">
        @csrf
        <label for="stationA">Ga Đi:</label>
        <input type="text" name="stationA" id="stationA" required>

        <label for="stationB">Ga Đến:</label>
        <input type="text" name="stationB" id="stationB" required>

        <!-- <label>Loại vé:</label> -->
        <label>
            <input type="radio" name="ticketType" value="one-way" checked onclick="toggleReturnDate()"> Một chiều
        </label>
        <label>
            <input type="radio" name="ticketType" value="round-trip" onclick="toggleReturnDate()"> Khứ hồi
        </label>

        <label for="departureDate">Ngày Đi:</label>
        <input type="date" name="departureDate" id="departureDate" required>

        <label for="returnDate">Ngày Về:</label>
        <input type="date" name="returnDate" id="returnDate" disabled>
        <br>
        <button type="submit">Tìm Kiếm</button>
    </form>
</div>

    <script>
        function toggleReturnDate() {
            const returnDateInput = document.getElementById('returnDate');
            const isRoundTrip = document.querySelector('input[name="ticketType"]:checked').value === 'round-trip';
            returnDateInput.disabled = !isRoundTrip;
            returnDateInput.required = isRoundTrip;
        }
    </script>
    <style>
        label {
            display: block;
            margin-top: 10px;
        }
    </style>
@endsection
