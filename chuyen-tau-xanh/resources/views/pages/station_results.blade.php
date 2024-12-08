@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Kết quả tìm kiếm tàu</h3>

    <div class="mb-4">
        <p><strong>Ga đi:</strong> {{ $gaDi }}</p>
        <p><strong>Ga đến:</strong> {{ $gaDen }}</p>
        <p><strong>Ngày đi:</strong> {{ \Carbon\Carbon::parse($ngay)->format('d/m/Y') }}</p>
    </div>

    <div class="row">
        @foreach ($routes as $route)
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $route->train_mark }}</h5>
                        <p class="card-text">
                            <strong>Ngày đi:</strong> {{ \Carbon\Carbon::parse($ngay)->format('d/m/Y') }}
                        </p>
                        <p class="card-text">
                            <strong>Giờ khởi hành:</strong> {{ \Carbon\Carbon::parse($route->departure_time)->format('H:i') }}
                        </p>

                        @php
                            $departureTime = \Carbon\Carbon::parse($route->departure_time);
                            $arrivalTime = \Carbon\Carbon::parse($route->arrival_time);
                            $arrivalDate = \Carbon\Carbon::parse($ngay);

                            if ($arrivalTime < $departureTime) {
                                $arrivalDate->addDay(); 
                            }
                        @endphp
                        <p class="card-text">
                            <strong>Ngày đến:</strong> 
                            {{ $arrivalDate->format('d/m/Y') }}
                        </p>
                        <p class="card-text">
                            <strong>Giờ đến:</strong> 
                            {{ $arrivalTime->format('H:i') }}
                        </p>

                        <a href="{{ route('train.details', ['id' => $route->id, 'gaDi' => $gaDi, 'gaDen' => $gaDen, 'ngay' => $ngay]) }}" class="btn btn-primary">Xem chi tiết</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
