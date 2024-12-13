@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="text-primary text-center mb-4">KẾT QUẢ TÌM KIẾM TÀU</h2>

        <div class="alert alert-info">
            <p><strong>Ga đi:</strong> {{ $gaDi }}</p>
            <p><strong>Ga đến:</strong> {{ $gaDen }}</p>
            <p><strong>Ngày đi:</strong> {{ \Carbon\Carbon::parse($ngay)->format('d/m/Y') }}</p>
        </div>

        <div class="row">
            @foreach ($routes as $route)
                <div class="col-md-4 mb-4">
                    <div class="card shadow-lg rounded">
                        <div class="card-body">
                            <h5 class="card-title text-center text-primary">{{ $route->train_mark }}</h5>
                            <p class="card-text">
                                <strong>Ngày đi:</strong> {{ \Carbon\Carbon::parse($ngay)->format('d/m/Y') }}
                            </p>
                            <p class="card-text">
                                <strong>Giờ khởi hành:</strong>
                                {{ \Carbon\Carbon::parse($route->departure_time)->format('H:i') }}
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

                            <a href="{{ route('train.details', ['id' => $route->id, 'gaDi' => $gaDi, 'gaDen' => $gaDen, 'ngay' => $ngay]) }}"
                                class="btn btn-primary w-100">Xem chi tiết</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.1);
        }

        .text-primary {
            color: #0056b3 !important;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .mb-4 {
            margin-bottom: 1.5rem !important;
        }
    </style>
@endpush
