@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <!-- Tiêu đề trang -->
        <h3 class="text-center mb-4 text-primary font-weight-bold">Thông tin hành trình</h3>

        <!-- Thông tin tàu, ga đi, ga đến -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="gaDi" class="font-weight-bold">Ga đi:</label>
                    <input type="text" class="form-control" id="gaDi" value="{{ $gaDi }}" readonly>
                </div>
                <div class="form-group">
                    <label for="ngayDi" class="font-weight-bold">Ngày đi:</label>
                    <input type="text" class="form-control" id="ngayDi"
                        value="{{ \Carbon\Carbon::parse($ngay)->format('d/m/Y') }}" readonly>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="gaDen" class="font-weight-bold">Ga đến:</label>
                    <input type="text" class="form-control" id="gaDen" value="{{ $gaDen }}" readonly>
                </div>
                <div class="form-group">
                    <label for="trainMark" class="font-weight-bold">Tàu:</label>
                    <input type="text" class="form-control" id="trainMark" value="{{ $route->train_mark }}" readonly>
                </div>
            </div>
        </div>

        <hr>

        <!-- Danh sách các ga trong hành trình -->
        <h4 class="mb-4 text-secondary">Các ga trong hành trình</h4>
        <table class="table table-striped table-bordered">
            <thead class="bg-primary text-white">
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
                    $baseDate = \Carbon\Carbon::parse($ngay);
                    $gaDiIndex = $routeStations->firstWhere('station.station_name', $gaDi)->date_index ?? 0;
                @endphp

                @foreach ($routeStations as $station)
                    @php
                        $dateOffset = $station->date_index - $gaDiIndex;
                        $adjustedDate = $baseDate->copy()->addDays($dateOffset);
                        $departureTime = \Carbon\Carbon::parse(
                            $adjustedDate->format('Y-m-d') . ' ' . $station->departure_time,
                        );
                        $arrivalTime = \Carbon\Carbon::parse(
                            $adjustedDate->format('Y-m-d') . ' ' . $station->arrival_time,
                        );
                    @endphp
                    <tr @if ($station->station->station_name == $gaDi || $station->station->station_name == $gaDen) style="font-weight: bold;" @endif>
                        <td>{{ $counter++ }}</td>
                        <td>{{ $station->station->station_name }}</td>
                        <td>{{ $adjustedDate->format('d/m/Y') }}</td>
                        <td>{{ $departureTime->format('H:i') }}</td>
                        <td>{{ $arrivalTime->format('H:i') }}</td>
                        <td>{{ $station->km }} km</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <hr>

        <!-- Bảng giá vé -->
        <h4 class="mb-4 text-secondary">Bảng giá vé</h4>
        <table class="table table-striped table-bordered">
            <thead class="bg-primary text-white">
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
                        <td>{{ number_format($seatType->price * 1000, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
