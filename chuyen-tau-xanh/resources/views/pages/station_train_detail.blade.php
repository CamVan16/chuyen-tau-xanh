{{-- @extends('layouts.app')

@section('content')
<div class="container">
    <h3>Thông tin hành trình</h3>
    <div class="row">
        <div class="col-md-12">
            <h4>Tàu {{ $route->train_mark }}</h4>
            <p>Ga đi: {{ $route->routeStations->first()->station->station_name }}</p>
            <p>Ngày đi: {{ $route->routeStations->first()->departure_date }}</p>
            <p>Ga đến: {{ $route->routeStations->last()->station->station_name }}</p>
            <p>Ngày đến: {{ $route->routeStations->last()->departure_date }}</p>
            <p>Thời gian hành trình: {{ \Carbon\Carbon::parse($route->routeStations->last()->arrival_time)->diffInMinutes(\Carbon\Carbon::parse($route->routeStations->first()->departure_time)) }} phút</p>
        </div>
    </div>

    <h4>Các ga trong hành trình</h4>
    <table class="table">
        <thead>
            <tr>
                <th>STT</th>
                <th>Ga đi</th>
                <th>Ngày đi</th>
                <th>Giờ đi</th>
                <th>Giờ đến</th>
            </tr>
        </thead>
        <tbody>
            @foreach($route->routeStations as $index => $station)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $station->station->station_name }}</td>
                <td>{{ $station->departure_date }}</td>
                <td>{{ $station->departure_time }}</td>
                <td>{{ $station->adjusted_arrival_time->format('H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h4>Bảng giá vé</h4>
    <table class="table">
        <thead>
            <tr>
                <th>STT</th>
                <th>Loại chỗ</th>
                <th>Giá vé (₫)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($route->routeStations as $index => $station)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $station->seat_type }}</td>
                <td>{{ $station->price }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection --}}

@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Thông tin hành trình</h3>
    <div class="row">
        <div class="col-md-12">
            <h4>Tàu {{ $route->train_mark }}</h4>
            
            <!-- Preserve gaDi and gaDen for the selected route -->
            <p><strong>Ga đi:</strong> {{ $gaDi }}</p>
            <p><strong>Ngày đi:</strong> {{ \Carbon\Carbon::parse($departureDate)->format('d/m/Y') }}</p>
            <p><strong>Ga đến:</strong> {{ $gaDen }}</p>
            <p><strong>Ngày đến:</strong> {{ \Carbon\Carbon::parse($arrivalDate)->format('d/m/Y') }}</p>
            <p><strong>Thời gian hành trình:</strong> {{ \Carbon\Carbon::parse($arrivalDate)->diffInMinutes(\Carbon\Carbon::parse($departureDate)) }} phút</p>
        </div>
    </div>

    <h4>Các ga trong hành trình</h4>
    <table class="table">
        <thead>
            <tr>
                <th>STT</th>
                <th>Ga đi</th>
                <th>Ngày đi</th>
                <th>Giờ đi</th>
                <th>Giờ đến</th>
            </tr>
        </thead>
        <tbody>
            @foreach($route->routeStations as $index => $station)
                @php
                    $departureTime = \Carbon\Carbon::parse($station->departure_time);
                    $arrivalTime = \Carbon\Carbon::parse($station->arrival_time);
                    
                    // Create a new arrival date based on departure date
                    $arrivalDate = \Carbon\Carbon::parse($departureDate);

                    if ($arrivalTime < $departureTime) {
                        $arrivalDate->addDay(); // Adjust if the arrival time is before the departure time
                    }
                @endphp

                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td 
                        @if ($station->station->station_name == $gaDi || $station->station->station_name == $gaDen)
                            style="font-weight: bold;"
                        @endif
                    >{{ $station->station->station_name }}</td>
                    <td>
                        <!-- Show adjusted departure date if necessary -->
                        {{ \Carbon\Carbon::parse($station->departure_date)->format('d/m/Y') }}
                    </td>
                    <td>{{ \Carbon\Carbon::parse($station->departure_time)->format('H:i') }}</td>
                    <td>{{ $arrivalTime->format('H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h4>Bảng giá vé</h4>
    <table class="table">
        <thead>
            <tr>
                <th>STT</th>
                <th>Loại chỗ</th>
                <th>Giá vé (₫)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($route->routeStations as $index => $station)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $station->seat_type }}</td>
                    <td>{{ number_format($station->price, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
