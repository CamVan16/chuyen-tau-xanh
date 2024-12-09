@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Thông tin hành trình</h3>
    <div class="row">
        <div class="col-md-12">
            <h4>Tàu {{ $route->train_mark }}</h4>

            <!-- Hiển thị thông tin ga đi, ga đến -->
            <p><strong>Ga đi:</strong> {{ $gaDi }}</p>
            <p><strong>Ngày đi:</strong> {{ \Carbon\Carbon::parse($ngay)->format('d/m/Y') }}</p>
            <p><strong>Ga đến:</strong> {{ $gaDen }}</p>
        </div>
    </div>

    <h4>Các ga trong hành trình</h4>
    <table class="table">
        <thead>
            <tr>
                <th>STT</th>
                <th>Ga</th>
                <th>Ngày đi</th>
                <th>Giờ đi</th>
                <th>Giờ đến</th>
                <th>Cự ly (km)</th>
            </tr>
        </thead>
        <tbody>
            @php
                $counter = 1; 
                $currentDate = \Carbon\Carbon::parse($ngay); 
                $lastDepartureTime = \Carbon\Carbon::parse($ngay . ' ' . $routeStations->first()->departure_time); 
            @endphp
            @foreach($routeStations as $station)
                @php
                    $departureTime = \Carbon\Carbon::parse($currentDate->format('Y-m-d') . ' ' . $station->departure_time);
                    $arrivalTime = \Carbon\Carbon::parse($currentDate->format('Y-m-d') . ' ' . $station->arrival_time);

                    // Nếu giờ đi nhỏ hơn giờ đi trước đó, tăng ngày
                    if ($departureTime->lt($lastDepartureTime)) {
                        $currentDate->addDay();
                        $departureTime = $departureTime->addDay();
                        $arrivalTime = $arrivalTime->addDay();
                    }

                    // Cập nhật thời gian đi cuối cùng
                    $lastDepartureTime = $departureTime;
                @endphp
                <tr>
                    <td>{{ $counter++ }}</td> <!-- Sử dụng biến đếm STT -->
                    <td 
                        @if ($station->station->station_name == $gaDi || $station->station->station_name == $gaDen)
                            style="font-weight: bold;"
                        @endif
                    >
                        {{ $station->station->station_name }}
                    </td>
                    <td>{{ $currentDate->format('d/m/Y') }}</td>
                    <td>{{ $departureTime->format('H:i') }}</td>
                    <td>{{ $arrivalTime->format('H:i') }}</td>
                    <td>{{ $station->km }} km</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h4>Bảng giá vé</h4>
<table class="table">
    <thead>
        <tr>
            <th>STT</th>
            <th>Mã loại chỗ</th>
            <th>Tên loại chỗ</th>
            <th>Giá vé (₫)</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($seatTypes as $index => $seatType)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $seatType->seat_type_code }}</td>
                <td>{{ $seatType->seat_type_name }}</td>
                <td>{{ number_format($seatType->price, 0, ',', '.') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>


</div>
@endsection






