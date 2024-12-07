@extends('layouts.app')

@section('title', 'Đặt chỗ')
@section('head')
    @parent
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <style>
        .go-trains, .return-trains {
            display: flex;
        }
        .train {
            display: inline;
            border: 1px solid black;
            width: 150px;
            height: 150px;
            border-radius: 10%;
            margin: 20px;
            text-align: center;
            cursor: pointer;
        }
        .train.active {
            background-color: lightblue;
        }
        .car.active {
            background-color: lightgreen;
        }
        .cars {
            margin-top: 20px;
            display: flex;
        }
        .car {
            border: 1px solid black;
            width: 50px;
            height: 20px;
            margin: 2px;
            border-radius: 10%;
            background-color: lightgray;
            font-size: 14px;
            text-align: center;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function renderCars(cars, container) {
                const $container = $(container);
                $container.empty();
                if (cars.length > 0) {
                    cars.forEach(car => {
                        $container.append(`<div data-id="${car.id}" 
                                                data-name="${car.car_name}"
                                                data-description="${car.car_description}"
                                                class="car"
                                            >
                                                ${car.car_name}
                                            </div>`);
                    });
                } else {
                    $container.text('Không có toa nào cho tàu này');
                }
            }
            function renderSeats(seats, container) {
                const $container = $(container);
                $container.empty();
                if (seats.length > 0) {
                    seats.forEach(seat => {
                        $container.append(`<div class="seat">Ghế: ${seat.seat_index}</div>`);
                    });
                } else {
                    $container.text('Không có ghế nào cho toa này');
                }
            }

            const $firstGoTrain = $('.go-trains .train').first();
            if ($firstGoTrain.length) {
                $firstGoTrain.addClass('active');
                const defaultCars = $firstGoTrain.data('cars');
                renderCars(defaultCars, '#go-cars-container');
            }

            const $firstReturnTrain = $('.return-trains .train').first();
            if ($firstReturnTrain.length) {
                $firstReturnTrain.addClass('active');
                const defaultCars = $firstReturnTrain.data('cars');
                renderCars(defaultCars, '#return-cars-container');
            }

            $('.go-trains .train').on('click', function () {
                const $this = $(this);
                $('.go-trains .train').removeClass('active');
                $this.addClass('active');
                const cars = $this.data('cars');
                renderCars(cars, '#go-cars-container');
            });

            $('.return-trains .train').on('click', function () {
                const $this = $(this);
                $('.return-trains .train').removeClass('active');
                $this.addClass('active');
                const cars = $this.data('cars');
                renderCars(cars, '#return-cars-container');
            });

            $(document).on('click', '#go-cars-container .car', function () {
                const $this = $(this);
                $('#go-cars-container .car').removeClass('active');
                $this.addClass('active');
                var carId = parseInt($this.data('id'));
                $('.go-car-description').text(`Toa số ${$this.data('name')}: ${$this.data('description')}`)
                $.post("/timkiem/ketqua", { car_id: carId }, function (data, status) {
                    renderSeats(data, '#go-seats-container');
                }).fail(function () {
                    alert('Không thể tải danh sách ghế. Vui lòng thử lại.');
                });
            });

            $(document).on('click', '#return-cars-container .car', function () {
                const $this = $(this);
                $('#return-cars-container .car').removeClass('active');
                $this.addClass('active');
                var carId = parseInt($this.data('id'));
                $('.return-car-description').text(`Toa số ${$this.data('name')}: ${$this.data('description')}`)
                $.post("/timkiem/ketqua", { car_id: carId }, function (data, status) {
                    renderSeats(data, '#return-seats-container');
                }).fail(function () {
                    alert('Không thể tải danh sách ghế. Vui lòng thử lại.');
                });
            });

        });
    </script>
<div>
    <?php
        $stationA = $_POST['stationA'];
        $stationB = $_POST['stationB'];
        $departureDate = $_POST['departureDate'];
        $ticketType = $_POST['ticketType'];
        $returnDate = isset($_POST['returnDate']) ? $_POST['returnDate'] : null;
    ?>
    <h3>Chiều đi: ngày {{$departureDate}} từ {{$stationA}} đến {{$stationB}}</h3>
    <div class="go-trains">
        @forelse($goRoutes as $route)
            <div class="train"
                data-cars='@json($route->cars)'
            >
                <h4>{{ $route->train_mark }}</h4>
                <p>TG đi {{ $departureDate }} {{ $route->departure_time }}</p>
                <p>
                    TG đến 
                    <?php
                        $departureDateObj = new DateTime($departureDate);
                        $daysToAdd = $route->arrival_date_index - $route->departure_date_index;
                        $departureDateObj->modify("+$daysToAdd days");
                        echo $departureDateObj->format('Y-m-d');
                    ?>
                    {{ $route->arrival_time }}
                </p>
            </div>
        @empty
            <p>Không tìm thấy tuyến tàu phù hợp.</p>
        @endforelse
    </div>
    <div id="go-cars-container" class="cars"></div>
    <h4 class="go-car-description"></h4>
    <div id="go-seats-container" class="seats"></div>

    @if ($ticketType === 'round-trip')
        <h3>Chiều về: ngày {{$returnDate}} từ {{$stationB}} đến {{$stationA}}</h3>
        <div class="return-trains">
            @forelse($returnRoutes as $route)
                <div class="train"
                    data-cars='@json($route->cars)'
                >
                    <h4>Tuyến {{ $route->train_mark }}</h4>
                    <!-- <h4>Tàu {{ $route->train }}</h4> -->
                    <p>TG đi {{ $returnDate }} {{ $route->departure_time }}</p>
                    <p>
                        TG đến 
                        <?php
                            $returnDateObj = new DateTime($returnDate);
                            $daysToAdd = $route->arrival_date_index - $route->departure_date_index;
                            $returnDateObj->modify("+$daysToAdd days");
                            echo $returnDateObj->format('Y-m-d');
                        ?>
                        {{ $route->arrival_time }}
                    </p>
                </div>
            @empty
                <p>Không tìm thấy tuyến tàu phù hợp.</p>
            @endforelse
        </div>
        <div id="return-cars-container" class="cars"></div>
        <h4 class="return-car-description"></h4>
        <div id="return-seats-container" class="seats"></div>
    @endif
</div>
@endsection