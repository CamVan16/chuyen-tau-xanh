@extends('layouts.app')

@section('content')
<script>
    $(document).ready(function () {
        const timers = new Map();
        function renderTickets() {
            const tickets = JSON.parse(localStorage.getItem('ticket-pocket')) || [];
            if (tickets.length > 0) {
                const departureTickets = tickets.filter(ticket => ticket.direction === 1);
                const returnTickets = tickets.filter(ticket => ticket.direction === -1);
    
                const matchedRoundTrips = [];
                const unmatchedDepartures = [...departureTickets];
                const unmatchedReturns = [...returnTickets];
    
                departureTickets.forEach(departure => {
                    const matchIndex = unmatchedReturns.findIndex(returnTicket => 
                        returnTicket.to_station === departure.from_station &&
                        returnTicket.from_station === departure.to_station &&
                        new Date(returnTicket.departure_date) > new Date(departure.arrival_date)
                    );
    
                    if (matchIndex !== -1) {
                        const matchedReturn = unmatchedReturns.splice(matchIndex, 1)[0];
                        matchedRoundTrips.push({ departure, return: matchedReturn });
                        unmatchedDepartures.splice(unmatchedDepartures.findIndex(d => d === departure), 1);
                    }
                });
    
                $('#round-trip-tickets').empty();
                $('#departure-tickets').empty();
                $('#return-tickets').empty();
    
                matchedRoundTrips.forEach(pair => {
                    const { departure, return: returnTicket } = pair;
                    $('#round-trip-tickets').append(`
                        <tr>
                            <td colspan="2">
                                Họ tên: <input type="text" />
                                Đối tượng: <input type="text" /> 
                                Số giấy tờ: <input type="text" />
                            </td>
                            <td>${departure.train_mark} ${departure.from_station} - ${departure.to_station} 
                                ${departure.departure_date} ${departure.departure_time}
                                Toa ${departure.car} chỗ ${departure.seat_index}
                            </td>
                            <td>${departure.price}</td>
                            <td>0</td>
                            <td>0</td>
                            <td>1</td>
                            <td>1</td>
                            <td>1</td>
                        </tr>
                        <tr>
                            <td>${returnTicket.train_mark} ${returnTicket.from_station} - ${returnTicket.to_station} 
                                ${returnTicket.departure_date} ${returnTicket.departure_time}
                                Toa ${returnTicket.car} chỗ ${returnTicket.seat_index}
                            </td>
                            <td>${returnTicket.price}</td>
                            <td>0</td>
                            <td>0</td>
                            <td>1</td>
                            <td>1</td>
                            <td>1</td>
                        </tr>
                    `);
                });
    
                unmatchedDepartures.forEach(ticket => {
                    $('#departure-tickets').append(`
                        <tr>
                            <td>
                                Họ tên: <input type="text" />
                                Đối tượng: <input type="text" /> 
                                Số giấy tờ: <input type="text" />
                            </td>
                            <td>${ticket.train_mark} ${ticket.from_station} - ${ticket.to_station} 
                                ${ticket.departure_date} ${ticket.departure_time}
                                Toa ${ticket.car} chỗ ${ticket.seat_index}
                            </td>
                            <td>${ticket.price}</td>
                            <td>0</td>
                            <td>0</td>
                            <td>1</td>
                            <td>1</td>
                            <td>1</td>
                        </tr>
                    `);
                });
    
                unmatchedReturns.forEach(ticket => {
                    $('#return-tickets').append(`
                        <tr>
                            <td>
                                Họ tên: <input type="text" />
                                Đối tượng: <input type="text" /> 
                                Số giấy tờ: <input type="text" />
                            </td>
                            <td>${ticket.train_mark} ${ticket.from_station} - ${ticket.to_station} 
                                ${ticket.departure_date} ${ticket.departure_time}
                                Toa ${ticket.car} chỗ ${ticket.seat_index}
                            </td>
                            <td>${ticket.price}</td>
                            <td>0</td>
                            <td>0</td>
                            <td>1</td>
                            <td>1</td>
                            <td>1</td>
                        </tr>
                    `);
                });
            } 
        }
        function loadCart() {
            var tickets = JSON.parse(localStorage.getItem('ticket-pocket')) || [];
            console.log(tickets);
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
                        renderTickets();
                    }, remaining); 
                    timers.set(ticket.seat_id, timer); 
                    return true;
                } else {
                    return false;
                }
            });
            localStorage.setItem('ticket-pocket', JSON.stringify(tickets));
            renderTickets();
        }
        loadCart();
        
        $('.next-btn').on('click', function () {
            const nextStep = $(this).data('next');
            $('.step').hide();
            $('#' + nextStep).show();

            // Nếu là GĐ2, hiển thị thông tin xác nhận
            if (nextStep === 'step-2') {
                $('#confirmation-info').html(`
                    <p><strong>Tên hành khách:</strong> ${$('#passenger-name').val()}</p>
                    <p><strong>Số căn cước:</strong> ${$('#passenger-id').val()}</p>
                    <p><strong>Tên người đặt:</strong> ${$('#booker-name').val()}</p>
                    <p><strong>Số di động:</strong> ${$('#booker-phone').val()}</p>
                    <p><strong>Phương thức thanh toán:</strong> ${$('#payment-method option:selected').text()}</p>
                `);
            }
        });

        $('.back-btn').on('click', function () {
            const prevStep = $(this).data('prev');
            $('.step').hide();
            $('#' + prevStep).show();
        });

    });
</script>
<div class="container">
    <div id="booking-steps">
        <!-- Giai đoạn 1: Nhập thông tin -->
        <div class="step" id="step-1">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Thông tin hành khách</th>
                        <th>Thông tin chỗ</th>
                        <th>Giá vé</th>
                        <th>Giảm giá</th>
                        <th>Khuyến mãi</th>
                        <th>Bảo hiểm</th>
                        <th>Thành tiền</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="round-trip-tickets">
                    <!-- Vé khứ hồi -->
                </tbody>
                <tbody id="departure-tickets">
                    <!-- Vé chiều đi -->
                </tbody>
                <tbody id="return-tickets">
                    <!-- Vé chiều về -->
                </tbody>
            </table>
            <button type="button" class="btn btn-primary next-btn" data-next="step-2">Tiếp tục</button>
        </div>

        <!-- Giai đoạn 2: Xác nhận thông tin -->
        <div class="step" id="step-2" style="display:none;">
            <h3>Xác nhận thông tin</h3>
            <div id="confirmation-info">
                <!-- Thông tin sẽ được hiển thị từ dữ liệu nhập -->
            </div>
            <button type="button" class="btn btn-secondary back-btn" data-prev="step-1">Quay lại</button>
            <button type="button" class="btn btn-primary next-btn" data-next="step-3">Xác nhận</button>
        </div>

        <!-- Giai đoạn 3: Thanh toán -->
        <div class="step" id="step-3" style="display:none;">
            <h3>Thanh toán</h3>
            <p>Đang điều hướng đến cổng thanh toán...</p>
            <!-- Thêm JavaScript để điều hướng hoặc xử lý thanh toán -->
        </div>

        <!-- Giai đoạn 4: Hoàn tất -->
        <div class="step" id="step-4" style="display:none;">
            <h3>Đặt vé thành công!</h3>
            <p>Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi!</p>
        </div>
    </div>
</div>
@endsection
