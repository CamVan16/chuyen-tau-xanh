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
        .seat {
            width: 25px;
            height: 25px;
            border: 1px solid black;
            text-align: center;
            /* align-items: center; */
            padding: 0;
        }
        .seat.reserve {
            background-color: orange;
        }
        .compartment {
            text-align: center;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            const timers = new Map();
            // var tickets = [];
            var groutes = $('.go-routes').data('groutes');
            var rroutes = $('.return-routes').data('rroutes');
            // console.log(groutes);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function renderCars(cars, container, trainMark) {
                const $container = $(container);
                $container.empty();
                if (cars.length > 0) {
                    cars.forEach(car => {
                        $container.append(`<div data-id="${car.id}" 
                                                data-name="${car.car_name}"
                                                data-description="${car.car_description}"
                                                data-count="${car.num_of_seats}"
                                                data-layout="${car.car_layout}"
                                                data-mark="${trainMark}"
                                                class="car"
                                            >
                                                ${car.car_name}
                                            </div>`);
                    });
                } else {
                    $container.text('Không có toa nào cho tàu này');
                }
            }
            function renderSeats(seats, container, carName, carLayout, numOfSeats, trainMark) {
                const $container = $(container);
                $container.empty();
                if (seats.length > 0) {
                    if (carLayout % 2 === 0) {
                        const rows = 4;
                        const cols = numOfSeats / rows;
                        for (let i = 0; i < rows; i++) {
                            const $rowDiv = $('<div class="d-flex justify-content-center mb-3"></div>');
    
                            for (let j = 0; j < cols; j++) {
                                let seatNumber;
                                if (j * rows + i < seats.length) {
                                    if (j % 2 === 0) {
                                        seatNumber = i + 1 + j * rows;
                                    } else {
                                        seatNumber = rows - i + j * rows;
                                    }
                                    const $seatDiv = $(`<div class="seat m-2" 
                                                            data-index="${seatNumber}"
                                                            data-id="${seats[seatNumber-1]?.id}"
                                                            data-type="${seats[seatNumber-1]?.seat_type}"
                                                            data-car="${carName}"
                                                            data-mark="${trainMark}"
                                                        >
                                                        ${seatNumber}
                                                        </div>`);
                                    $rowDiv.append($seatDiv);
                                }
                            }
                            $container.append($rowDiv);
                        }
                    } else {
                        if (carLayout === 7) {
                            const $rowDiv = $('<div class="d-flex justify-content-center m-3"></div>');
                            for (let i = 0; i < 7; i++) {
                                const $compartmentDiv = $(`<div class="compartment"></div>`);
                                for (let j = 1; j <= 3; j++) {
                                    const $layer = $(`<div class="layer d-flex justify-content-center m-3"></div>`);
                                    $layer.append(`<div class="seat"
                                                        data-index="${i * 6 + (j * 2) - 1}"
                                                        data-id="${seats[i * 6 + (j * 2) - 1-1]?.id}"
                                                        data-type="${seats[i * 6 + (j * 2) - 1-1]?.seat_type}"
                                                        data-car="${carName}"
                                                        data-mark="${trainMark}"
                                                    >${i * 6 + (j * 2) - 1}</div>`);
                                    $layer.append(`<div class="seat"
                                                        data-index="${i * 6 + (j * 2)}"
                                                        data-id="${seats[i * 6 + (j * 2)-1]?.id}"
                                                        data-type="${seats[i * 6 + (j * 2)-1]?.seat_type}"
                                                        data-car="${carName}"
                                                        data-mark="${trainMark}"
                                                    >${i * 6 + (j * 2)}</div>`);
                                    $compartmentDiv.prepend($layer)
                                }
                                $compartmentDiv.prepend(`<span>Khoang ${i + 1}</span>`)
                                $rowDiv.append($compartmentDiv);
                            }
                            $container.append($rowDiv);
                        } else if (carLayout === 5) {
                            const $rowDiv = $('<div class="d-flex justify-content-center m-3"></div>');
                            for (let i = 0; i < 7; i++) {
                                const $compartmentDiv = $(`<div class="compartment"></div>`);
                                for (let j = 1; j <= 2; j++) {
                                    const $layer = $(`<div class="layer d-flex justify-content-center m-3"></div>`);
                                    $layer.append(`<div class="seat"
                                                        data-index="${i * 4 + (j * 2) - 1}"
                                                        data-id="${seats[i * 4 + (j * 2) - 1-1]?.id}"
                                                        data-type="${seats[i * 4 + (j * 2) - 1-1]?.seat_type}"
                                                        data-car="${carName}"
                                                        data-mark="${trainMark}"
                                                    >${i * 4 + (j * 2) - 1}</div>`);
                                    $layer.append(`<div class="seat"
                                                        data-index="${i * 4 + (j * 2)}"
                                                        data-id="${seats[i * 4 + (j * 2)-1]?.id}"
                                                        data-type="${seats[i * 4 + (j * 2)-1]?.seat_type}"
                                                        data-car="${carName}"
                                                        data-mark="${trainMark}"
                                                    >${i * 4 + (j * 2)}</div>`);
                                    $compartmentDiv.prepend($layer)
                                }
                                $compartmentDiv.prepend(`<span>Khoang ${i + 1}</span>`)
                                $rowDiv.append($compartmentDiv);
                            }
                            $container.append($rowDiv);
                        } else if (carLayout === 27) {
                            const t1 = [1, 2, 5, 6, 9, 10, 11, 12, 13, 14, 17, 18, 21, 22];
                            const t2 = [3, 4, 7, 8, null, null, null, null, 15, 16, 19, 20, 23, 24];
                            const $rowDiv = $('<div class="d-flex justify-content-center m-3"></div>');
                            for (let i = 0; i < 7; i++) {
                                const $compartmentDiv = $(`<div class="compartment"></div>`);
                                for (let j = 1; j <= 2; j++) {
                                    const $layer = $(`<div class="layer d-flex justify-content-center m-3"></div>`);
                                    if (j === 1) {
                                        // if (t1[i*2]) {
                                            $layer.append(`<div class="seat"
                                                                data-index="${t1[i * 2]}"
                                                                data-id="${seats[(t1[i * 2])-1]?.id}"
                                                                data-type="${seats[(t1[i * 2])-1]?.seat_type}"
                                                                data-car="${carName}"
                                                                data-mark="${trainMark}"
                                                            >${t1[i * 2]}</div>`);
                                            $layer.append(`<div class="seat"
                                                                data-index="${t1[i * 2 + 1]}"
                                                                data-id="${seats[(t1[i * 2 + 1])-1]?.id}"
                                                                data-type="${seats[(t1[i * 2 + 1])-1]?.seat_type}"
                                                                data-car="${carName}"
                                                                data-mark="${trainMark}"
                                                            >${t1[i * 2 + 1]}</div>`);
                                        // }
                                    } else {
                                        // if (t2[i*2]) {
                                            $layer.append(`<div class="seat"
                                                                data-index="${t2[i * 2]}"
                                                                data-id="${seats[(t2[i * 2])-1]?.id}"
                                                                data-type="${seats[(t2[i * 2])-1]?.seat_type}"
                                                                data-car="${carName}"
                                                                data-mark="${trainMark}"
                                                            >${t2[i * 2]}</div>`);
                                            $layer.append(`<div class="seat"
                                                                data-index="${t2[i * 2 + 1]}"
                                                                data-id="${seats[(t2[i * 2 + 1])-1]?.id}"
                                                                data-type="${seats[(t2[i * 2 + 1])-1]?.seat_type}"
                                                                data-car="${carName}"
                                                                data-mark="${trainMark}"
                                                            >${t2[i * 2 + 1]}</div>`);
                                        // }
                                    }
                                    $compartmentDiv.prepend($layer)
                                }
                                $compartmentDiv.prepend(`<span>Khoang ${i + 1}</span>`)
                                $rowDiv.append($compartmentDiv);
                            }
                            $container.append($rowDiv);
                        } else if (carLayout === 25) {
                            const t1 = [1, 2, 5, 6, 9, 10, 11, 12, 15, 16, 19, 20];
                            const t2 = [3, 4, 7, 8, null, null, 13, 14, 17, 18, 21, 22];
                            const $rowDiv = $('<div class="d-flex justify-content-center m-3"></div>');
                            for (let i = 0; i < 6; i++) {
                                const $compartmentDiv = $(`<div class="compartment"></div>`);
                                for (let j = 1; j <= 2; j++) {
                                    const $layer = $(`<div class="layer d-flex justify-content-center m-3"></div>`);
                                    if (j === 1) {
                                        // if (t1[i*2]) {
                                            $layer.append(`<div class="seat"
                                                                data-index="${t1[i * 2]}"
                                                                data-id="${seats[(t1[i * 2])-1]?.id}"
                                                                data-type="${seats[(t1[i * 2])-1]?.seat_type}"
                                                                data-car="${carName}"
                                                                data-mark="${trainMark}"
                                                            >${t1[i * 2]}</div>`);
                                            $layer.append(`<div class="seat"
                                                                data-index="${t1[i * 2 + 1]}"
                                                                data-id="${seats[(t1[i * 2 + 1])-1]?.id}"
                                                                data-type="${seats[(t1[i * 2 + 1])-1]?.seat_type}"
                                                                data-car="${carName}"
                                                                data-mark="${trainMark}"
                                                            >${t1[i * 2 + 1]}</div>`);
                                        // }
                                    } else {
                                        // if (t2[i*2]) {
                                            $layer.append(`<div class="seat"
                                                                data-index="${t2[i * 2]}"
                                                                data-id="${seats[(t2[i * 2])-1]?.id}"
                                                                data-type="${seats[(t2[i * 2])-1]?.seat_type}"
                                                                data-car="${carName}"
                                                                data-mark="${trainMark}"
                                                            >${t2[i * 2]}</div>`);
                                            $layer.append(`<div class="seat"
                                                                data-index="${t2[i * 2 + 1]}"
                                                                data-id="${seats[(t2[i * 2 + 1])-1]?.id}"
                                                                data-type="${seats[(t2[i * 2 + 1])-1]?.seat_type}"
                                                                data-car="${carName}"
                                                                data-mark="${trainMark}"
                                                            >${t2[i * 2 + 1]}</div>`);
                                        // }
                                    }
                                    $compartmentDiv.prepend($layer)
                                }
                                $compartmentDiv.prepend(`<span>Khoang ${i + 1}</span>`)
                                $rowDiv.append($compartmentDiv);
                            }
                            $container.append($rowDiv);
                        } else if (carLayout === 13) {
                            const t1 = [1, 2, 3, 4, 7, 8, 11, 12, 15, 16, 19, 20, 23, 24];
                            const t2 = [null, null, 5, 6, 9, 10, 13, 14, 17, 18, 21, 22, 25, 26];
                            const $rowDiv = $('<div class="d-flex justify-content-center m-3"></div>');
                            for (let i = 0; i < 7; i++) {
                                const $compartmentDiv = $(`<div class="compartment"></div>`);
                                for (let j = 1; j <= 2; j++) {
                                    const $layer = $(`<div class="layer d-flex justify-content-center m-3"></div>`);
                                    if (j === 1) {
                                        // if (t1[i*2]) {
                                            $layer.append(`<div class="seat"
                                                                data-index="${t1[i * 2]}"
                                                                data-id="${seats[(t1[i * 2])-1]?.id}"
                                                                data-type="${seats[(t1[i * 2])-1]?.seat_type}"
                                                                data-car="${carName}"
                                                                data-mark="${trainMark}"
                                                            >${t1[i * 2]}</div>`);
                                            $layer.append(`<div class="seat"
                                                                data-index="${t1[i * 2 + 1]}"
                                                                data-id="${seats[(t1[i * 2 + 1])-1]?.id}"
                                                                data-type="${seats[(t1[i * 2 + 1])-1]?.seat_type}"
                                                                data-car="${carName}"
                                                                data-mark="${trainMark}"
                                                            >${t1[i * 2 + 1]}</div>`);
                                        // }
                                    } else {
                                        // if (t2[i*2]) {
                                            $layer.append(`<div class="seat"
                                                                data-index="${t2[i * 2]}"
                                                                data-id="${seats[(t2[i * 2])-1]?.id}"
                                                                data-type="${seats[(t2[i * 2])-1]?.seat_type}"
                                                                data-car="${carName}"
                                                                data-mark="${trainMark}"
                                                            >${t2[i * 2]}</div>`);
                                            $layer.append(`<div class="seat"
                                                                data-index="${t2[i * 2 + 1]}"
                                                                data-id="${seats[(t2[i * 2 + 1])-1]?.id}"
                                                                data-type="${seats[(t2[i * 2 + 1])-1]?.seat_type}"
                                                                data-car="${carName}"
                                                                data-mark="${trainMark}"
                                                            >${t2[i * 2 + 1]}</div>`);
                                        // }
                                    }
                                    $compartmentDiv.prepend($layer)
                                }
                                $compartmentDiv.prepend(`<span>Khoang ${i + 1}</span>`)
                                $rowDiv.append($compartmentDiv);
                            }
                            $container.append($rowDiv);
                        }
                    }
                } else {
                    $container.text('Không có ghế nào cho toa này');
                }
            }
            function applyPrice(seatDOMs, direction) {
                const $seats = $(seatDOMs);
                $.map($seats, function(seat) {
                    const $seat = $(seat);
                    const seat_type = $seat.data('type');
                    const train = $seat.data('mark');
                    var route;
                    if (direction === 1) {
                        route = groutes.find(function(route) {
                            return route.train_mark === train;
                        });
                        $seat.attr('data-ddeparture', route.departure_date);
                        $seat.attr('data-tdeparture', route.departure_time);
                        $seat.attr('data-darrival', route.arrival_date);
                        $seat.attr('data-tarrival', route.arrival_time);
                    } else {
                        route = rroutes.find(function(route) {
                            return route.train_mark === train;
                        });
                        $seat.attr('data-ddeparture', route.departure_date);
                        $seat.attr('data-tdeparture', route.departure_time);
                        $seat.attr('data-darrival', route.arrival_date);
                        $seat.attr('data-tarrival', route.arrival_time);
                    }
                    const type = route.seat_types.find(function(type) {
                        return type.seat_type_code === seat_type;
                    });

                    if (type) {
                        const price = type.price;
                        $seat.attr('data-price', price);
                    }
                })
            }
            const $firstGoTrain = $('.go-trains .train').first();
            if ($firstGoTrain.length) {
                $firstGoTrain.addClass('active');
                const defaultGoCars = $firstGoTrain.data('cars');
                const trainMarkGo = $firstGoTrain.data('mark');
                // const departureDateGo = $firstGoTrain.data('date');
                // const departureTimeGo = $firstGoTrain.data('time');
                renderCars(defaultGoCars, '#go-cars-container', trainMarkGo);
            }

            const $firstReturnTrain = $('.return-trains .train').first();
            if ($firstReturnTrain.length) {
                $firstReturnTrain.addClass('active');
                const defaultReturnCars = $firstReturnTrain.data('cars');
                const trainMarkReturn = $firstReturnTrain.data('mark');
                // const departureDateReturn = $firstReturnTrain.data('date');
                // const departureTimeReturn = $firstReturnTrain.data('time');
                renderCars(defaultReturnCars, '#return-cars-container', trainMarkReturn);
            }

            $('.go-trains .train').on('click', function () {
                const $this = $(this);
                $('.go-trains .train').removeClass('active');
                $this.addClass('active');
                const cars = $this.data('cars');
                const trainMark = $this.data('mark');
                // const departureDate = $this.data('date');
                // const departureTime = $this.data('time');
                renderCars(cars, '#go-cars-container', trainMark);
            });

            $('.return-trains .train').on('click', function () {
                const $this = $(this);
                $('.return-trains .train').removeClass('active');
                $this.addClass('active');
                const cars = $this.data('cars');
                const trainMark = $this.data('mark');
                // const departureDate = $this.data('date');
                // const departureTime = $this.data('time');
                renderCars(cars, '#return-cars-container', trainMark);
            });

            $(document).on('click', '#go-cars-container .car', function () {
                const $this = $(this);
                $('#go-cars-container .car').removeClass('active');
                $this.addClass('active');
                var carId = parseInt($this.data('id'));
                var carLayout = parseInt($this.data('layout'));
                var numOfSeats = parseInt($this.data('count'));
                var trainMark = $this.data('mark');
                var departureDate = $this.data('date');
                var departureTime = $this.data('time');
                var carName = $this.data('name');
                $('.go-car-description').text(`Toa số ${$this.data('name')}: ${$this.data('description')}`)
                $.post("/timkiem/ketqua", { car_id: carId }, function (data, status) {
                    // console.log("Dữ liệu trả về:", data);
                    renderSeats(data, '#go-seats-container', carName, carLayout, numOfSeats, trainMark);
                    applyPrice('#go-seats-container .seat', 1);
                }).fail(function () {
                    alert('Không thể tải danh sách ghế. Vui lòng thử lại.');
                });
            });

            $(document).on('click', '#return-cars-container .car', function () {
                const $this = $(this);
                $('#return-cars-container .car').removeClass('active');
                $this.addClass('active');
                var carId = parseInt($this.data('id'));
                var carLayout = parseInt($this.data('layout'));
                var numOfSeats = parseInt($this.data('count'));
                var trainMark = $this.data('mark');
                var departureDate = $this.data('date');
                var departureTime = $this.data('time');
                var carName = $this.data('name');
                $('.return-car-description').text(`Toa số ${$this.data('name')}: ${$this.data('description')}`)
                $.post("/timkiem/ketqua", { car_id: carId }, function (data, status) {
                    renderSeats(data, '#return-seats-container', carName, carLayout, numOfSeats, trainMark);
                    applyPrice('#return-seats-container .seat', -1);
                }).fail(function () {
                    alert('Không thể tải danh sách ghế. Vui lòng thử lại.');
                });
            });

            function updateCart() {
                const $cart = $('.cart');
                $cart.empty();
                const tickets = JSON.parse(localStorage.getItem('ticket-pocket')) || [];
                if (tickets.length === 0) {
                    $cart.append('<p>Chưa có vé.</p>');
                    return;
                }
                const $ticketList = $('<ul class="ticket-list"></ul>');
                tickets.forEach(ticket => {
                    const $ticketItem = $(`
                        <li class="ticket-item">
                            ${ticket.from_station} - ${ticket.to_station}
                            Khởi hành: ${ticket.departure_date} lúc ${ticket.departure_time}
                            Tàu: ${ticket.train_mark} <br>
                            Ghế số: ${ticket.seat_index} - Loại: ${ticket.seat_type} - Giá: ${ticket.price}
                            Toa: ${ticket.car} <br>
                            <button class="remove-ticket btn btn-sm btn-danger" data-id="${ticket.seat_id}">
                                Xóa
                            </button>
                        </li>
                    `);
                    $ticketList.append($ticketItem);
                });
                $cart.append($ticketList);
                const $checkoutButton = $('<button class="checkout-tickets btn btn-primary mt-3">Mua vé</button>');
                $cart.append($checkoutButton);

            }
            
            loadCart();
            function loadCart() {
                var tickets = JSON.parse(localStorage.getItem('ticket-pocket')) || [];
                // console.log(tickets);
                const now = Date.now();
                tickets = tickets.filter(ticket => {
                    const elapsed = now - ticket.start_time;
                    const remaining = 600000 - elapsed;
                    if (remaining > 0) {
                        const timer = setTimeout(() => {
                            let cart = JSON.parse(localStorage.getItem('ticket-pocket')) || [];
                            cart = cart.filter(item => item.seat_id !== ticket.seat_id);
                            localStorage.setItem('ticket-pocket', JSON.stringify(cart));
                            timers.delete(ticket.seat_id); 
                            updateCart();
                        }, remaining); 
                        timers.set(ticket.seat_id, timer); 
                        return true;
                    } else {
                        return false;
                    }
                });
                localStorage.setItem('ticket-pocket', JSON.stringify(tickets));
                updateCart();
            }

            function addTicket(ticket) {
                const tickets = JSON.parse(localStorage.getItem('ticket-pocket')) || [];
                ticket.start_time = Date.now();
                tickets.push(ticket);
                localStorage.setItem('ticket-pocket', JSON.stringify(tickets));
                const timer = setTimeout(() => {
                    let cart = JSON.parse(localStorage.getItem('ticket-pocket')) || [];
                    cart = cart.filter(item => item.seat_id !== ticket.seat_id);
                    localStorage.setItem('ticket-pocket', JSON.stringify(cart));
                    timers.delete(ticket.seat_id);
                    updateCart();
                }, 600000);
                timers.set(ticket.seat_id, timer);
            }

            function removeTicket(seat_id) {
                let tickets = JSON.parse(localStorage.getItem('ticket-pocket')) || [];
                tickets = tickets.filter(ticket => ticket.seat_id !== seat_id);
                localStorage.setItem('ticket-pocket', JSON.stringify(tickets));
                if (timers.has(seat_id)) {
                    clearTimeout(timers.get(seat_id));
                    timers.delete(seat_id);
                }
            }

            $(document).on('click', '#go-seats-container .seat', function () {
                const $seat = $(this);
                const ticket = {
                    from_station: $('.stationA').text(),
                    to_station: $('.stationB').text(),
                    direction: 1,
                    seat_id: $seat.data('id'),
                    seat_index: $seat.data('index'),
                    seat_type: $seat.data('type'),
                    car: $seat.data('car'),
                    train_mark: $seat.data('mark'),
                    departure_date: $seat.data('ddeparture'),
                    departure_time: $seat.data('tdeparture'),
                    arrival_date: $seat.data('darrival'),
                    arrival_time: $seat.data('tarrival'),
                    price: $seat.data('price'),
                };
                const tickets = JSON.parse(localStorage.getItem('ticket-pocket')) || [];
                const seatIndex = tickets.findIndex((s) => s.seat_id === ticket.seat_id);
                if (seatIndex !== -1) {
                    removeTicket(ticket.seat_id);
                } else {
                    addTicket(ticket);
                }
                updateCart();
            });

            $(document).on('click', '#return-seats-container .seat', function () {
                const $seat = $(this);
                const ticket = {
                    from_station: $('.stationB').text(),
                    to_station: $('.stationA').text(),
                    direction: -1,
                    seat_id: $seat.data('id'),
                    seat_index: $seat.data('index'),
                    seat_type: $seat.data('type'),
                    car: $seat.data('car'),
                    train_mark: $seat.data('mark'),
                    departure_date: $seat.data('ddeparture'),
                    departure_time: $seat.data('tdeparture'),
                    arrival_date: $seat.data('darrival'),
                    arrival_time: $seat.data('tarrival'),
                    price: $seat.data('price'),
                };
                const tickets = JSON.parse(localStorage.getItem('ticket-pocket')) || [];
                const seatIndex = tickets.findIndex((s) => s.seat_id === ticket.seat_id);
                if (seatIndex !== -1) {
                    removeTicket(ticket.seat_id);
                } else {
                    addTicket(ticket);
                }
                updateCart();
            });
            
            $(document).on('click', '.checkout-tickets', function () {
                const tickets = JSON.parse(localStorage.getItem('ticket-pocket')) || [];
                if (tickets.length > 0) {
                    const form = $('<form action="{{ route('booking.form') }}" method="POST">@csrf</form>');

                    // tickets.forEach((ticket, index) => {
                    //     Object.keys(ticket).forEach(key => {
                    //         const input = $('<input>', {
                    //             type: 'hidden',
                    //             name: `tickets[${index}][${key}]`,
                    //             value: ticket[key]
                    //         });
                    //         form.append(input);
                    //     });
                    // });

                    $('body').append(form);
                    form.submit();
                }
            });

            $(document).on('click', '.remove-ticket', function () {
                const seatId = parseInt($(this).data('id'));
                removeTicket(seatId);
                updateCart();
            });

        });
    </script>
<div class="row">
    <div class="col-xs-12 col-sm-9 col-md-9">
        <?php
            $stationA = $_POST['stationA'];
            $stationB = $_POST['stationB'];
            $departureDate = $_POST['departureDate'];
            $ticketType = $_POST['ticketType'];
            $returnDate = isset($_POST['returnDate']) ? $_POST['returnDate'] : null;
        ?>
        <div class="go-routes d-none" data-groutes='@json($goRoutes)'></div>
        <h3>Chiều đi: ngày {{$departureDate}} từ <span class="stationA">{{$stationA}}</span> đến <span class="stationB">{{$stationB}}</span></h3>
        <div class="go-trains">
            @forelse($goRoutes as $route)
                <div class="train"
                    data-cars='@json($route->cars)'
                    data-mark = "{{ $route->train_mark }}"
                >
                    <h4>{{ $route->train_mark }}</h4>
                    <p>TG đi <span class="go-departure-date">{{ $departureDate }}</span> <span class="go-departure-time">{{ $route->departure_time }}</span></p>
                    <p>
                        TG đến <span class="go-arrival-date">{{ $route->arrival_date }}</span>
                        <span class="go-arrival-time">{{ $route->arrival_time }}</span>
                    </p>
                </div>
            @empty
                <p>Không tìm thấy tuyến tàu phù hợp.</p>
            @endforelse
        </div>
        <div id="go-cars-container" class="cars"></div>
        <h4 class="go-car-description"></h4>
        <div id="go-seats-container" class="seats justify-content-center"></div>
    
        @if ($ticketType === 'round-trip')
            <div class="return-routes d-none" data-rroutes='@json($returnRoutes)'></div>
            <h3>Chiều về: ngày {{$returnDate}} từ {{$stationB}} đến {{$stationA}}</h3>
            <div class="return-trains">
                @forelse($returnRoutes as $route)
                    <div class="train"
                        data-cars='@json($route->cars)'
                        data-mark = "{{ $route->train_mark }}"
                    >
                        <h4>Tuyến {{ $route->train_mark }}</h4>
                        <!-- <h4>Tàu {{ $route->train }}</h4> -->
                        <p>TG đi <span class="return-departure-date">{{ $returnDate }}</span> <span class="return-departure-time">{{ $route->departure_time }}</span></p>
                        <p>
                            TG đến <span class="return-arrival-date">{{ $route->arrival_date }}</span>
                            <span class="return-arrival-time">{{ $route->arrival_time }}</span>
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
    <div class="col-xs-12 col-sm-3 col-md-3 part-right">
        <div class="cart border">
            <div class="go-tickets" style="display:none;"></div>
            <div class="return-tickets" style="display:none;"></div>
        </div>
    </div>
</div>
@endsection