@extends('layouts.app')

@section('title', 'Đặt chỗ')
@section('head')
    @parent
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <style>
        .go-trains,
        .return-trains {
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
            background-color: #c6e7ff;
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
            background-color: #c6e7ff;
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
            background-color: #feee91;
        }

        .seat[data-status="1"] {
            pointer-events: none;
            background-color: #ff8a8a;
            color: white;
        }

        .compartment {
            text-align: center;
        }

        .ticket-item {
            margin-bottom: 16px;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            padding: 8px;
            background-color: #f9f9f9;
            display: flex;
            flex-direction: column;
        }

        .ticket-item .train-info {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .ticket-item .train-info p {
            margin: 0 0;
        }

        .ticket-item .ticket-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
        }

        .ticket-item .ticket-footer .ticket-price {
            margin: 0;
        }

        .ticket-item .remove-btn {
            background-color: transparent;
            border: none;
            color: #dc3545;
            cursor: pointer;
            font-size: 18px;
            display: flex;
            align-items: center;
        }

        .ticket-item .remove-btn i {
            margin-right: 5px;
        }

        .ticket-item .remove-btn:hover {
            color: #c82333;
        }

        .cart .btn {
            display: block;
            width: 50%;
            text-align: center;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script>
        var from_station = "{{ $ticket->schedule->station_start }}";
        var to_station = "{{ $ticket->schedule->station_end }}";
        var ticket_discount = "{{ $ticket->discount_price }}";
        var departure_date = "{{ \Carbon\Carbon::parse($ticket->schedule->date_start)->format('d/m/Y') }}";
        var exchange_fee = "{{ $ticket->exchange_fee * $ticket->price }}";
        $(document).ready(function() {
            const timers = new Map();
            var groutes = $('.go-routes').data('groutes');
            console.log(groutes);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function renderCars(cars, container, trainMark) {
                console.log('Danh sách cars:', cars);
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
                                    const $seatDiv = $(`<button class="btn seat m-2"
                                                            data-index="${seatNumber}"
                                                            data-status="${seats[seatNumber-1]?.seat_status}"
                                                            data-id="${seats[seatNumber-1]?.id}"
                                                            data-type="${seats[seatNumber-1]?.seat_type}"
                                                            data-car="${carName}"
                                                            data-mark="${trainMark}"
                                                        >
                                                        ${seatNumber}
                                                        </button>`);
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
                                    $layer.append(`<button class="btn seat"
                                                        data-index="${i * 6 + (j * 2) - 1}"
                                                        data-status="${seats[i * 6 + (j * 2) - 1-1]?.seat_status}"
                                                        data-id="${seats[i * 6 + (j * 2) - 1-1]?.id}"
                                                        data-type="${seats[i * 6 + (j * 2) - 1-1]?.seat_type}"
                                                        data-car="${carName}"
                                                        data-mark="${trainMark}"
                                                    >${i * 6 + (j * 2) - 1}</button>`);
                                    $layer.append(`<button class="btn seat"
                                                        data-index="${i * 6 + (j * 2)}"
                                                        data-status="${seats[i * 6 + (j * 2)-1]?.seat_status}"
                                                        data-id="${seats[i * 6 + (j * 2)-1]?.id}"
                                                        data-type="${seats[i * 6 + (j * 2)-1]?.seat_type}"
                                                        data-car="${carName}"
                                                        data-mark="${trainMark}"
                                                    >${i * 6 + (j * 2)}</button>`);
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
                                    $layer.append(`<button class="btn seat"
                                                        data-index="${i * 4 + (j * 2) - 1}"
                                                        data-status="${seats[i * 4 + (j * 2) - 1-1]?.seat_status}"
                                                        data-id="${seats[i * 4 + (j * 2) - 1-1]?.id}"
                                                        data-type="${seats[i * 4 + (j * 2) - 1-1]?.seat_type}"
                                                        data-car="${carName}"
                                                        data-mark="${trainMark}"
                                                    >${i * 4 + (j * 2) - 1}</button>`);
                                    $layer.append(`<button class="btn seat"
                                                        data-index="${i * 4 + (j * 2)}"
                                                        data-status="${seats[i * 4 + (j * 2)-1]?.seat_status}"
                                                        data-id="${seats[i * 4 + (j * 2)-1]?.id}"
                                                        data-type="${seats[i * 4 + (j * 2)-1]?.seat_type}"
                                                        data-car="${carName}"
                                                        data-mark="${trainMark}"
                                                    >${i * 4 + (j * 2)}</button>`);
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
                                        $layer.append(`<button class="btn seat"
                                                                data-index="${t1[i * 2]}"
                                                                data-status="${seats[(t1[i * 2])-1]?.seat_status}"
                                                                data-id="${seats[(t1[i * 2])-1]?.id}"
                                                                data-type="${seats[(t1[i * 2])-1]?.seat_type}"
                                                                data-car="${carName}"
                                                                data-mark="${trainMark}"
                                                            >${t1[i * 2]}</button>`);
                                        $layer.append(`<button class="btn seat"
                                                                data-index="${t1[i * 2 + 1]}"
                                                                data-status="${seats[(t1[i * 2 + 1])-1]?.seat_status}"
                                                                data-id="${seats[(t1[i * 2 + 1])-1]?.id}"
                                                                data-type="${seats[(t1[i * 2 + 1])-1]?.seat_type}"
                                                                data-car="${carName}"
                                                                data-mark="${trainMark}"
                                                            >${t1[i * 2 + 1]}</button>`);
                                        // }
                                    } else {
                                        // if (t2[i*2]) {
                                        $layer.append(`<button class="btn seat"
                                                                data-index="${t2[i * 2]}"
                                                                data-status="${seats[(t2[i * 2])-1]?.seat_status}"
                                                                data-id="${seats[(t2[i * 2])-1]?.id}"
                                                                data-type="${seats[(t2[i * 2])-1]?.seat_type}"
                                                                data-car="${carName}"
                                                                data-mark="${trainMark}"
                                                            >${t2[i * 2]}</button>`);
                                        $layer.append(`<button class="btn seat"
                                                                data-index="${t2[i * 2 + 1]}"
                                                                data-status="${seats[(t2[i * 2 + 1])-1]?.seat_status}"
                                                                data-id="${seats[(t2[i * 2 + 1])-1]?.id}"
                                                                data-type="${seats[(t2[i * 2 + 1])-1]?.seat_type}"
                                                                data-car="${carName}"
                                                                data-mark="${trainMark}"
                                                            >${t2[i * 2 + 1]}</button>`);
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
                                        $layer.append(`<button class="btn seat"
                                                                data-index="${t1[i * 2]}"
                                                                data-status="${seats[(t1[i * 2])-1]?.seat_status}"
                                                                data-id="${seats[(t1[i * 2])-1]?.id}"
                                                                data-type="${seats[(t1[i * 2])-1]?.seat_type}"
                                                                data-car="${carName}"
                                                                data-mark="${trainMark}"
                                                            >${t1[i * 2]}</button>`);
                                        $layer.append(`<button class="btn seat"
                                                                data-index="${t1[i * 2 + 1]}"
                                                                data-status="${seats[(t1[i * 2 + 1])-1]?.seat_status}"
                                                                data-id="${seats[(t1[i * 2 + 1])-1]?.id}"
                                                                data-type="${seats[(t1[i * 2 + 1])-1]?.seat_type}"
                                                                data-car="${carName}"
                                                                data-mark="${trainMark}"
                                                            >${t1[i * 2 + 1]}</button>`);
                                        // }
                                    } else {
                                        // if (t2[i*2]) {
                                        $layer.append(`<button class="btn seat"
                                                                data-index="${t2[i * 2]}"
                                                                data-status="${seats[(t2[i * 2])-1]?.seat_status}"
                                                                data-id="${seats[(t2[i * 2])-1]?.id}"
                                                                data-type="${seats[(t2[i * 2])-1]?.seat_type}"
                                                                data-car="${carName}"
                                                                data-mark="${trainMark}"
                                                            >${t2[i * 2]}</button>`);
                                        $layer.append(`<button class="btn seat"
                                                                data-index="${t2[i * 2 + 1]}"
                                                                data-status="${seats[(t2[i * 2 + 1])-1]?.seat_status}"
                                                                data-id="${seats[(t2[i * 2 + 1])-1]?.id}"
                                                                data-type="${seats[(t2[i * 2 + 1])-1]?.seat_type}"
                                                                data-car="${carName}"
                                                                data-mark="${trainMark}"
                                                            >${t2[i * 2 + 1]}</button>`);
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
                                        $layer.append(`<button class="btn seat"
                                                                data-index="${t1[i * 2]}"
                                                                data-status="${seats[(t1[i * 2])-1]?.seat_status}"
                                                                data-id="${seats[(t1[i * 2])-1]?.id}"
                                                                data-type="${seats[(t1[i * 2])-1]?.seat_type}"
                                                                data-car="${carName}"
                                                                data-mark="${trainMark}"
                                                            >${t1[i * 2]}</button>`);
                                        $layer.append(`<button class="btn seat"
                                                                data-index="${t1[i * 2 + 1]}"
                                                                data-status="${seats[(t1[i * 2 + 1])-1]?.seat_status}"
                                                                data-id="${seats[(t1[i * 2 + 1])-1]?.id}"
                                                                data-type="${seats[(t1[i * 2 + 1])-1]?.seat_type}"
                                                                data-car="${carName}"
                                                                data-mark="${trainMark}"
                                                            >${t1[i * 2 + 1]}</button>`);
                                        // }
                                    } else {
                                        // if (t2[i*2]) {
                                        $layer.append(`<button class="btn seat"
                                                                data-index="${t2[i * 2]}"
                                                                data-status="${seats[(t2[i * 2])-1]?.seat_status}"
                                                                data-id="${seats[(t2[i * 2])-1]?.id}"
                                                                data-type="${seats[(t2[i * 2])-1]?.seat_type}"
                                                                data-car="${carName}"
                                                                data-mark="${trainMark}"
                                                            >${t2[i * 2]}</button>`);
                                        $layer.append(`<button class="btn seat"
                                                                data-index="${t2[i * 2 + 1]}"
                                                                data-status="${seats[(t2[i * 2 + 1])-1]?.seat_status}"
                                                                data-id="${seats[(t2[i * 2 + 1])-1]?.id}"
                                                                data-type="${seats[(t2[i * 2 + 1])-1]?.seat_type}"
                                                                data-car="${carName}"
                                                                data-mark="${trainMark}"
                                                            >${t2[i * 2 + 1]}</button>`);
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
                    } else {
                        route = rroutes.find(function(route) {
                            return route.train_mark === train;
                        });
                    }
                    $seat.attr('data-ddeparture', route.departure_date);
                    $seat.attr('data-tdeparture', route.departure_time);
                    $seat.attr('data-darrival', route.arrival_date);
                    $seat.attr('data-tarrival', route.arrival_time);
                    const ratio = route.ratio;
                    const type = route.seat_types.find(function(type) {
                        return type.seat_type_code === seat_type;
                    });

                    if (type) {
                        const price = Math.round(type.price / ratio);
                        const description = type.seat_type_name;
                        $seat.attr('data-price', price);
                        $seat.attr('data-description', description);
                        $seat.attr('data-toggle', "popover");
                        $seat.attr('data-content', `Giá: ${(price*1000).toLocaleString()} VNĐ`);
                    }
                })
            }
            const $firstGoTrain = $('.go-trains .train').first();
            console.log('$firstGoTrain', $firstGoTrain)
            if ($firstGoTrain.length) {
                $firstGoTrain.addClass('active');
                const defaultGoCars = $firstGoTrain.data('cars');
                const trainMarkGo = $firstGoTrain.data('mark');
                renderCars(defaultGoCars, '#go-cars-container', trainMarkGo);
            }

            $('.go-trains .train').on('click', function() {
                const $this = $(this);
                $('.go-trains .train').removeClass('active');
                $this.addClass('active');
                const cars = $this.data('cars');
                const trainMark = $this.data('mark');
                // const departureDate = $this.data('date');
                // const departureTime = $this.data('time');
                $('#go-seats-container').empty();
                $('.go-car-description').empty();
                renderCars(cars, '#go-cars-container', trainMark);
            });

            $(document).on('click', '#go-cars-container .car', function() {
                const $this = $(this);
                $('#go-cars-container .car').removeClass('active');
                $this.addClass('active');
                var carId = parseInt($this.data('id'));
                var carLayout = parseInt($this.data('layout'));
                var numOfSeats = parseInt($this.data('count'));
                var trainMark = $this.data('mark');
                var departureDate = groutes.find(route => route.train_mark === trainMark).departure_date;
                // var departureTime = $this.data('time');
                var carName = $this.data('name');
                $('.go-car-description').text(`Toa số ${carName}: ${$this.data('description')}`)
                $.post("/timkiem/ketqua", {
                    car_id: carId,
                    car_name: carName,
                    train_mark: trainMark,
                    departure_date: departureDate
                }, function(data, status) {
                    renderSeats(data, '#go-seats-container', carName, carLayout, numOfSeats,
                        trainMark);
                    applyPrice('#go-seats-container .seat', 1);
                    $('[data-toggle="popover"]').popover({
                        trigger: 'hover',
                        placement: "top"
                    });
                }).fail(function() {
                    alert('Không thể tải danh sách ghế. Vui lòng thử lại.');
                });
            });

            function updateCart() {
                const $cart = $('.card-body');
                $cart.empty();
                $('.reserve').removeClass("reserve");

                let tickets = JSON.parse(localStorage.getItem('ticket-pocket')) || [];

                if (tickets.length > 0) {
                    tickets = [tickets[tickets.length - 1]];
                    localStorage.setItem('ticket-pocket', JSON.stringify(tickets));
                }

                if (tickets.length === 0) {
                    $cart.append('<p>Chưa có vé.</p>');
                    return;
                }

                const ticket = tickets[0];
                const $ticketItem = $(`
    <div class="ticket-item">
        <div class="train-info">
            <strong>${ticket.train_mark}</strong> ${ticket.from_station} → ${ticket.to_station}
            <p><strong>Khởi hành:</strong> ${ticket.departure_date} lúc ${ticket.departure_time}</p>
            <p>Toa: ${ticket.car} | Ghế: ${ticket.seat_index} | ${ticket.seat_type}</p>
        </div>
        <div class="ticket-footer">
            <p class="ticket-price text-success"><strong>${(ticket.price).toLocaleString()} VNĐ</strong></p>
            <button class="remove-btn remove-ticket" data-id="${ticket.seat_id}" data-train="${ticket.train_mark}">
                <i class="fas fa-trash"></i> Xóa
            </button>
        </div>
    </div>
`);
                $(`.seat[data-id="${ticket.seat_id}"][data-mark="${ticket.train_mark}"]`).addClass("reserve");
                $cart.append($ticketItem);

                const $checkoutButton = $('<button class="checkout-tickets btn btn-primary">Đổi vé</button>');
                $cart.append($checkoutButton);
                updateInfo();
            }


            loadCart();

            function loadCart() {
                var tickets = JSON.parse(localStorage.getItem('ticket-pocket')) || [];
                const now = Date.now();
                tickets = tickets.filter(ticket => {
                    const elapsed = now - ticket.start_time;
                    const remaining = 600000 - elapsed;
                    if (remaining > 0) {
                        const timer = setTimeout(() => {
                            let cart = JSON.parse(localStorage.getItem('ticket-pocket')) || [];
                            cart = cart.filter(item => !(item.seat_id === ticket.seat_id && item
                                .train_mark === ticket.train_mark));
                            localStorage.setItem('ticket-pocket', JSON.stringify(cart));
                            timers.delete({
                                id: ticket.seat_id,
                                train: ticket.train_mark
                            });
                            updateCart();
                        }, remaining);
                        timers.set({
                            id: ticket.seat_id,
                            train: ticket.train_mark
                        }, timer);
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
                    cart = cart.filter(item => !(item.seat_id === ticket.seat_id && item.train_mark ===
                        ticket.train_mark));
                    localStorage.setItem('ticket-pocket', JSON.stringify(cart));
                    timers.delete({
                        id: ticket.seat_id,
                        train: ticket.train_mark
                    });
                    updateCart();
                }, 600000);
                timers.set({
                    id: ticket.seat_id,
                    train: ticket.train_mark
                }, timer);
            }

            function removeTicket(obj) {
                let tickets = JSON.parse(localStorage.getItem('ticket-pocket')) || [];
                console.log(obj);
                // console.log(obj.train);
                tickets = tickets.filter(ticket => !(ticket.seat_id === obj.id && ticket.train_mark === obj.train));
                // console.log(tickets);
                localStorage.setItem('ticket-pocket', JSON.stringify(tickets));
                if (timers.has(obj)) {
                    clearTimeout(timers.get(obj));
                    timers.delete(obj);
                }
                updateInfo();
            }

            $(document).on('click', '#go-seats-container .seat', function() {
                const $seat = $(this);
                const ticket = {
                    from_station: from_station,
                    to_station: to_station,
                    direction: 1,
                    seat_id: $seat.data('id'),
                    seat_index: $seat.data('index'),
                    seat_type: $seat.data('type'),
                    seat_description: $seat.data('description'),
                    car: $seat.data('car'),
                    train_mark: $seat.data('mark'),
                    departure_date: $seat.data('ddeparture'),
                    departure_time: $seat.data('tdeparture'),
                    arrival_date: $seat.data('darrival'),
                    arrival_time: $seat.data('tarrival'),
                    price: parseInt($seat.data('price') * 1000),
                };
                const tickets = JSON.parse(localStorage.getItem('ticket-pocket')) || [];
                const seatIndex = tickets.findIndex((s) => s.seat_id === ticket.seat_id && s.train_mark ===
                    ticket.train_mark);
                if (seatIndex !== -1) {
                    removeTicket({
                        id: ticket.seat_id,
                        train: ticket.train_mark
                    });
                } else {
                    addTicket(ticket);
                }
                updateCart();
            });

            $(document).on('click', '.checkout-tickets', function() {
                const tickets = JSON.parse(localStorage.getItem('ticket-pocket')) || [];
                if (tickets.length > 0) {
                    const form = $(
                        '<form action="/booking" method="POST">@csrf</form>');

                    $('body').append(form);
                    form.submit();
                }
            });

            $(document).on('click', '.remove-ticket', function() {
                const seatId = parseInt($(this).data('id'));
                const train = $(this).data('train');
                removeTicket({
                    id: seatId,
                    train: train
                });
                updateCart();
            });

        });

        function updateInfo() {
            const tickets = JSON.parse(localStorage.getItem('ticket-pocket')) || [];
            const currentTicket = tickets[tickets.length - 1];
            const oldTotal = parseFloat($('#ticketSummary tr:nth-child(1) td:nth-child(9)').text().replace(/\./g, '')
                .trim());

            $('#ticketSummary tr').slice(1).remove();

            if (currentTicket) {
                const totalPrice = currentTicket.price - (currentTicket.price * ticket_discount || 0);
                const exchangeFee = parseFloat(exchange_fee);
                const totalAmount = totalPrice - oldTotal + exchangeFee;

                const isNegativeAmount = totalAmount < 0;
                const displayAmount = Math.abs(totalAmount); ;
                const row = `
            <tr>
                <td>Vé mới</td>
                <td>${currentTicket.train_mark}</td>
                <td>${currentTicket.from_station} → ${currentTicket.to_station}</td>
                <td>${departure_date} ${currentTicket.departure_time}</td>
                <td>${currentTicket.car}</td>
                <td>${currentTicket.seat_index}</td>
                <td>${currentTicket.price.toLocaleString()}</td>
                <td>${(currentTicket.price*ticket_discount).toLocaleString()}</td>
                <td>${totalPrice.toLocaleString()}</td>
            </tr>
        `;
                const feeRow = `<tr>
                        <td colspan="8" style="text-align: left;"><strong>Chi phí:</strong></td>
            <td><strong>${exchangeFee.toLocaleString()}</strong></td>
            </tr>`;
                const totalRow = `
            <tr>
                        <td colspan="8" style="text-align: left;"><strong>${isNegativeAmount ? 'Tiền thừa' : 'Tiền phải trả thực tế'}:</strong></td>
            <td><strong>${displayAmount.toLocaleString()}</strong></td>
            </tr>`;
                $('#ticketSummary').append(row);
                $('#ticketSummary').append(feeRow);
                $('#ticketSummary').append(totalRow);
            }
        }
    </script>
    <div class="row">
        <div class="col-xs-12 col-sm-9 col-md-9">

            <div class="go-routes d-none" data-groutes='@json($goRoutes)'></div>
            <h3>Thông tin đổi vé</h3>
            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th></th>
                        <th>Tàu</th>
                        <th>Chuyến</th>
                        <th>Giờ chạy</th>
                        <th>Toa</th>
                        <th>Ghế</th>
                        <th>Giá</th>
                        <th>Giá giảm</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody id="ticketSummary">
                    <tr>
                        <td>Vé cũ</td>
                        <td>{{ $ticket->schedule->train_mark }}</td>
                        <td>{{ $ticket->schedule->station_start }} → {{ $ticket->schedule->station_end }}</td>
                        <td>{{ \Carbon\Carbon::parse($ticket->schedule->date_start)->format('d/m/Y') }}
                            {{ $ticket->schedule->time_start }}</td>
                        <td>{{ $ticket->schedule->car_name }}</td>
                        <td>{{ $ticket->schedule->seat_number }}</td>
                        <td>{{ number_format($ticket->price, 0, ',', '.') }}</td>
                        <td>{{ number_format($ticket->price * $ticket->discount_price, 0, ',', '.') }}</td>
                        <td>{{ number_format($ticket->price * (1 - $ticket->discount_price), 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>

            <h3>Chiều đi: ngày {{ \Carbon\Carbon::parse($ticket->schedule->date_start)->format('d/m/Y') }} từ
                {{ $ticket->schedule->station_start }} đến {{ $ticket->schedule->station_end }}</h3>
            <div class="go-trains">
                @forelse($goRoutes as $route)
                    <div class="train" data-cars='@json($route->cars)' data-mark = "{{ $route->train_mark }}">
                        <h4>{{ $route->train_mark }}</h4>
                        <p>TG đi <span
                                class="go-departure-date">{{ \Carbon\Carbon::parse($ticket->schedule->date_start)->format('d/m/Y') }}</span>
                            <span class="go-departure-time">{{ $route->departure_time }}</span>
                        </p>
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
        </div>
        <div class="col-xs-12 col-sm-3 col-md-3">
            <div class="cart card border-secondary mb-3">
                <div class="card-header bg-primary text-white text-center">Giỏ vé</div>
                <div class="card-body">
                    <!-- Vé sẽ được render ở đây -->
                </div>
            </div>
        </div>
    </div>
@endsection
