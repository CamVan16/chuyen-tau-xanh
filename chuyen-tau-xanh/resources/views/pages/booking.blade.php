@extends('layouts.app')

@section('content')
<style>
    table p {
        margin: 0;
    }
</style>
<script>
    $(document).ready(function () {
        const timers = new Map();
        const vouchers = $('.all-vouchers').data('vouchers');
        // console.log('vouchers', vouchers);
        var matchedRoundTrips = [];
        var unmatchedDepartures = [];
        var unmatchedReturns = [];
        let currentSelect = null;
        let currentRow = null;
        function checkVouchers(price, type) {
            const eligibleVouchers = vouchers.filter(v => (v.min_price_order <= price && v.type === type));
            if (eligibleVouchers.length > 0) {
                return `
                    <button class="select-voucher-btn">Chọn khuyến mãi</button>`;
            } else {
                return `<span>Không có khuyến mãi nào cho vé này</span>`;
            }
        };
        function renderTicketByType(tickets, container, isRoundTrip = false) {
            const $container = $(container);
            $container.empty();
            if(tickets.length <= 0) {
                return;
            }
            tickets.forEach(ticket => {
                if (isRoundTrip) {
                    const { departure, return: returnTicket } = ticket;
                    $container.append(`
                        <tr data-seat="${departure.seat_id}" data-train="${departure.train_mark}">
                            <td rowspan="2">
                                Họ tên<span style="color: red">*</span>: <input type="text" required/><br />
                                Đối tượng: 
                                <select name="customer-type" class="customer-type">
                                    <option value="0">Người lớn</option>
                                    <option value="25">Trẻ em</option>
                                    <option value="10">Sinh viên</option>
                                    <option value="15">Người cao tuổi</option>
                                    <option value="5">Đoàn viên Công Đoàn</option>
                                </select><br />
                                Số giấy tờ<span style="color: red">*</span>: <input class="passenger-number" type="text" required/>
                            </td>
                            <td><p>${departure.train_mark} ${departure.from_station} - ${departure.to_station} </p>
                                <p>${departure.departure_date} ${departure.departure_time} </p>
                                <p>Toa ${departure.car} chỗ ${departure.seat_index} </p>
                                <p>${departure.seat_description} </p>
                            </td>
                            <td class="ticket-price">${departure.price.toLocaleString()}</td>
                            <td class="discount">0</td>
                            <td class="vouchers">${checkVouchers(departure.price, 0)}</td>
                            <td class="money">${departure.price.toLocaleString()}</td>
                            <td>1</td>
                        </tr>
                        <tr data-seat="${returnTicket.seat_id}" data-train="${returnTicket.train_mark}">
                            <td><p>${returnTicket.train_mark} ${returnTicket.from_station} - ${returnTicket.to_station} </p>
                                <p>${returnTicket.departure_date} ${returnTicket.departure_time} </p>
                                <p>Toa ${returnTicket.car} chỗ ${returnTicket.seat_index} </p>
                                <p>${returnTicket.seat_description} </p>
                            </td>
                            <td class="ticket-price">${returnTicket.price.toLocaleString()}</td>
                            <td class="discount">0</td>
                            <td class="vouchers">${checkVouchers(returnTicket.price, 0)}</td>
                            <td class="money">${returnTicket.price.toLocaleString()}</td>
                            <td>1</td>
                        </tr>
                    `);
                } else {
                    $container.append(`
                        <tr data-seat="${ticket.seat_id}" data-train="${ticket.train_mark}">
                            <td>
                                Họ tên<span style="color: red">*</span>: <input type="text" required/><br />
                                Đối tượng: 
                                <select name="customer-type" class="customer-type">
                                    <option value="0">Người lớn</option>
                                    <option value="25">Trẻ em</option>
                                    <option value="10">Sinh viên</option>
                                    <option value="15">Người cao tuổi</option>
                                    <option value="5">Đoàn viên Công Đoàn</option>
                                </select><br />
                                Số giấy tờ<span style="color: red">*</span>: <input class="passenger-number" type="text" required/>
                            </td>
                            <td><p>${ticket.train_mark} ${ticket.from_station} - ${ticket.to_station} </p>
                                <p>${ticket.departure_date} ${ticket.departure_time} </p>
                                <p>Toa ${ticket.car} chỗ ${ticket.seat_index} </p>
                                <p>${ticket.seat_description} </p>
                            </td>
                            <td class="ticket-price">${ticket.price.toLocaleString()}</td>
                            <td class="discount">0</td>
                            <td class="vouchers">${checkVouchers(ticket.price, 0)}</td>
                            <td class="money">${ticket.price.toLocaleString()}</td>
                            <td>1</td>
                        </tr>
                    `);
                }
            });
        }
        function renderTickets() {
            const tickets = JSON.parse(localStorage.getItem('ticket-pocket')) || [];
            const departureTickets = tickets.filter(ticket => ticket.direction === 1);
            const returnTickets = tickets.filter(ticket => ticket.direction === -1);

            // const matchedRoundTrips = [];
            unmatchedDepartures = [...departureTickets];
            unmatchedReturns = [...returnTickets];

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

            renderTicketByType(matchedRoundTrips, '#round-trip-tickets', true);
            renderTicketByType(unmatchedDepartures, '#departure-tickets');
            renderTicketByType(unmatchedReturns, '#return-tickets');
            updateTotalPrice();
        }
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
                        cart = cart.filter(item => !(item.seat_id === ticket.seat_id && item.train_mark === ticket.train_mark));
                        localStorage.setItem('ticket-pocket', JSON.stringify(cart));
                        timers.delete({id: ticket.seat_id, train: ticket.train_mark}); 
                        $('table tbody tr').filter(function () {
                            return $(this).data('seat') === ticket.seat_id && $(this).data('train') === ticket.train_mark;
                        }).remove();
                        updateTotalPrice();
                    }, remaining); 
                    timers.set({id: ticket.seat_id, train: ticket.train_mark}, timer); 
                    return true;
                } else {
                    return false;
                }
            });
            localStorage.setItem('ticket-pocket', JSON.stringify(tickets));
            renderTickets();
        }
        function calculateAge(birthdate) {
            const today = new Date();
            const birthDate = new Date(birthdate);
            let age = today.getFullYear() - birthDate.getFullYear();
            // const monthDiff = today.getMonth() - birthDate.getMonth();
            // if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
            //     age--;
            // }
            return age;
        }
        function updateTotalPrice() {
            let totalPrice = 0;
            $('table tbody tr').each(function () {
                const ticketPrice = parseInt($(this).find('.money').text().replace(/,/g, '')) || 0;
                totalPrice += ticketPrice;
            });
            $('.total-price').text(totalPrice.toLocaleString());
        }
        function updateMoney($row) {
            const ticketPrice = parseInt($row.find('.ticket-price').text().replace(/,/g, '')) || 0;
            const discountType = parseInt($row.find('.discount').text().replace(/,/g, '')) || 0;
            const $discountVoucherDOM = $row.find('.discount-voucher');
            const discountVoucher = ($discountVoucherDOM.length > 0) ? Math.min(parseInt($discountVoucherDOM.data('percent')) * ticketPrice, parseInt($discountVoucherDOM.data('max'))) : 0;
            const finalPrice = ticketPrice - discountType - discountVoucher;
            $row.find('.money').text(finalPrice.toLocaleString());
            updateTotalPrice();
        }
        function updateDiscount(selectElement) {
            const $row = $(selectElement).closest('tr'); 
            const ticketPrice = parseInt($row.find('.ticket-price').text().replace(/,/g, '')) || 0; 
            // console.log("ticketPrice", ticketPrice);
            const discountPercent = parseInt($(selectElement).val()) || 0; 
            const discountValue = Math.round((ticketPrice * discountPercent) / 100000) * 1000; 
            // console.log("discountValue", discountValue.toLocaleString());
            $row.find('.discount').text(discountValue.toLocaleString()); 
            const type = $(selectElement).find('option:selected').text();
            var typeVoucher;
            if (type === "Sinh viên") {
                typeVoucher = 1;
            } else if (type === "Trẻ em") {
                typeVoucher = 2;
            } else {
                typeVoucher = 0;
            }
            $row.find('.vouchers').html(checkVouchers(ticketPrice,typeVoucher));
            updateMoney($row);

            const $selectCell = $(selectElement).closest('td'); 
            if ($selectCell.attr('rowspan') == '2') {
                const $nextRow = $row.next('tr'); 
                const nextTicketPrice = parseInt($nextRow.find('.ticket-price').text().replace(/,/g, '')) || 0; 
                const nextDiscountValue = Math.round((nextTicketPrice * discountPercent) / 100000) * 1000; 
                $nextRow.find('.discount').text(nextDiscountValue.toLocaleString());
                $nextRow.find('.vouchers').html(checkVouchers(nextTicketPrice,typeVoucher));
                updateMoney($nextRow)
            }
        }
        $(document).on('click', '.select-voucher-btn', function () {
            const $row = $(this).closest('tr');
            const ticketPrice = parseInt($row.find('.ticket-price').text().replace(/,/g, '')) || 0; 
            var type;
            var $cusType = $row.find('.customer-type');
            if ($cusType.length === 0) {
                const $prevRow = $row.prev('tr'); 
                $cusType = $prevRow.find('.customer-type');
            }
            if ($cusType.find('option:selected').text() === 'Sinh viên') {
                type = 1;
            } else if ($cusType.find('option:selected').text() === 'Trẻ em') {
                type = 2;
            } else {
                type = 0;
            }
            currentRow = $row;
            const eligibleVouchers = vouchers.filter(v => v.min_price_order <= ticketPrice && v.type === type);
            const voucherList = eligibleVouchers.map(v => `
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <p>${v.name} - Giảm tối đa: ${v.max_price_discount.toLocaleString()} VNĐ
                - Áp dụng cho vé trên ${v.min_price_order.toLocaleString()} VNĐ</p>
                <button class="btn btn-success btn-sm select-this-voucher" data-name="${v.name}" data-percent="${v.percent}" data-max="${v.max_price_discount}">
                Chọn
                </button>
            </li>
            `).join('');
            $('#voucher-list').html(voucherList || '<li class="list-group-item">Không có khuyến mãi cho vé này</li>');
            $('#voucherModal').modal('show');
        });
        $('#voucher-list').on('click', '.select-this-voucher', function () {
            const voucherName = $(this).data('name');
            const percent = $(this).data('percent');
            const max = $(this).data('max');
            currentRow.find('.discount-voucher').empty();
            currentRow.find('.vouchers').prepend(`
                <p class="discount-voucher" data-percent="${percent}" data-max="${max}">${voucherName}</p>
            `);
            $('#voucherModal').modal('hide');
            updateMoney(currentRow);
        });
        $(document).on('change', '.customer-type', function () {
            const type = $(this).find('option:selected').text();
            const discountType = $(this).val();

            if (type === "Người cao tuổi" || type === "Trẻ em") {
                currentSelect = $(this);
                const modalMessage = type === "Người cao tuổi"
                    ? "Người cao tuổi (người từ 60 tuổi trở lên) được hưởng chính sách giảm giá theo quy định."
                    : "Trẻ em dưới 6 tuổi không cần mua vé. Trẻ em từ 6-10 tuổi được mua vé trẻ em.";

                $('#modalMessage').text(modalMessage);
                $('#birthdateInput').val(''); 
                $('#ageModal').modal('show');
            } else {
                updateDiscount(this);
            }
        });
        $('#confirmAge').on('click', function () {
            const birthdate = $('#birthdateInput').val();
            const $select = currentSelect; 
            const discountType = parseInt($select.val());
            const type = $select.find('option:selected').text();
            if (!birthdate) {
                alert('Vui lòng nhập ngày sinh!');
                return;
            }
            const age = calculateAge(birthdate);
            let validAge = false;
            if (type === "Người cao tuổi" && age >= 60) {
                validAge = true;
            } else if (type === "Trẻ em" && age >= 6 && age <= 10) {
                validAge = true;
            }
            if (validAge) {
                updateDiscount($select);
                const $selectCell = $select.closest('td'); 
                $selectCell.find('.passenger-number').val(birthdate);
                $('#ageModal').modal('hide'); 
            } else {
                alert('Tuổi không phù hợp để áp dụng giảm giá!');
                const $row = $select.closest('tr');
                $row.find('.discount').text('0');
                $('#ageModal').modal('hide'); 
            }
        });
        loadCart();
        function generateOrderId() {
            const characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
            let orderId = "";
            for (let i = 0; i < 8; i++) {
                const randomIndex = Math.floor(Math.random() * characters.length);
                orderId += characters[randomIndex];
            }
            return orderId;
        }
        
        $('.next-btn').on('click', function () {
            const nextStep = $(this).data('next');
            if (nextStep === 'step-2') {
                const emptyRequiredInputs = $('#step-1 input[required]').filter(function () {
                    return $(this).val().trim() === ""; 
                });
                if (emptyRequiredInputs.length > 0) {
                    alert("Vui lòng điền đầy đủ các trường bắt buộc!");
                    emptyRequiredInputs.first().focus();
                    return;
                }
                var cusTypes = [];
                $('#step-1').find('table option:selected').each(function () {
                    cusTypes.push($(this).text());
                });
                const $step1Content = $('#step-1').find('table').clone();
                $step1Content.find('input').each(function () {
                    $(this).attr('readonly', true); 
                });
                $step1Content.find('select').each(function (index, element) {
                    // console.log(cusTypes[index]);
                    $(this).after(`<input type="text" value="${cusTypes[index]}" readonly/>`);
                    $(this).remove();
                });
                const $step1Booker = $('#step-1').find('.booker-info').clone();
                $step1Booker.find('input').each(function () {
                    $(this).attr('readonly', true); 
                });
                // const step1Payment = $('#step-1').find('#payment_method').val();
                // const $payment = $('<p></p>')
                // if (step1Payment === 'vnpay') {
                //     $payment.text('Thanh toán trực tuyến qua cổng thanh toán VNPAY');
                // } else if (step1Payment === 'zalopay') {
                //     $payment.text('Thanh toán trực tuyến qua ví điện tử ZALOPAY');
                // } else if (step1Payment ==='momo') {
                //     $payment.text('Thanh toán trực tuyến qua ví điện tử MOMO');
                // }

                const $infoContainer = $('#confirmation-info');
                $infoContainer.empty(); 
                $infoContainer.append($step1Booker, $step1Content);
            }
            $('.step').hide();
            $('#' + nextStep).show();
            if (nextStep === 'step-3') {
                const amount = parseInt($('#step-1 .total-price').text().replace(/,/g, '')) || 0;
                const orderId = generateOrderId();

                $('#step-3 #order_id').val(orderId);
                $('#step-3 #amount').val(amount);
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
    <div class="d-none all-vouchers" data-vouchers='@json($vouchers)'></div>
    <div id="booking-steps">
        <div class="step" id="step-1">
            <h5>Thông tin giỏ vé</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Thông tin hành khách</th>
                        <th>Thông tin chỗ</th>
                        <th>Giá vé</th>
                        <th>Giảm giá</th>
                        <th>Khuyến mãi</th>
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
                <tfoot>
                    <tr>
                        <td colspan="6" class="text-right">Tổng tiền:</td>
                        <td colspan="2" class="total-price"></td>
                    </tr>
                </tfoot>
            </table>
            <div class="booker-info">
                <h5>Thông tin người đặt vé</h5>
                <div class="row">
                    <label class="col-xs-4 col-md-2">Họ và tên<span style="color: red">*</span>:</label>
                    <div class="col-xs-8 col-md-4">
                        <input type="text" name="fullName" required>
                    </div>
                    <label class="col-xs-4 col-md-2">Số CCCD/Hộ chiếu<span style="color: red">*</span>:</label>
                    <div class="col-xs-8 col-md-4">
                        <input type="text" name="indentity" required>
                    </div>
                </div>
                <div class="row">
                    <label class="col-xs-4 col-md-2">Email:</label>
                    <div class="col-xs-8 col-md-4">
                        <input type="text" name="email">
                    </div>
                    <label class="col-xs-4 col-md-2">Xác nhận email:</label>
                    <div class="col-xs-8 col-md-4">
                        <input type="text" name="emailConfirm">
                    </div>
                </div>
                <div class="row">
                    <label class="col-xs-4 col-md-2">Số di động<span style="color: red">*</span>:</label>
                    <div class="col-xs-8 col-md-4">
                        <input type="text" name="phoneNumber" required>
                    </div>
                </div>
            </div>
            <!-- <div>
                <h5>Phương thức thanh toán</h5>
                <select name="payment_method" id="payment_method" class="form-select" required>
                    <option value="vnpay">VNPay</option>
                    <option value="zalopay">ZaloPay</option>
                    <option value="momo">Momo</option>
                </select>
            </div> -->
            <button type="button" class="btn btn-primary next-btn" data-next="step-2">Tiếp tục</button>
        </div>

        <div class="step" id="step-2" style="display:none;">
            <h3>Xác nhận thông tin</h3>
            <div id="confirmation-info">
                <!-- Thông tin sẽ được hiển thị từ dữ liệu nhập -->
            </div>
            <button type="button" class="btn btn-secondary back-btn" data-prev="step-1">Quay lại</button>
            <button type="button" class="btn btn-primary next-btn" data-next="step-3">Xác nhận</button>
        </div>

        <div class="step" id="step-3" style="display:none;">
            <h3>Thanh toán</h3>
            <form action="{{ route('booking.processPayment') }}" method="POST" class="mt-4">
                @csrf
                <div class="mb-3">
                    <label for="order_id" class="form-label">Booking ID (Order ID):</label>
                    <input readonly type="text" name="order_id" id="order_id" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="amount" class="form-label">Tổng tiền (Total Price):</label>
                    <input readonly type="number" name="amount" id="amount" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="order_desc" class="form-label">Mô tả (Order Description):</label>
                    <input type="text" name="order_desc" id="order_desc" class="form-control" value="Thanh toán vé tàu" required>
                </div>
                <div class="mb-3">
                    <label for="order_type" class="form-label">Loại đơn hàng (Order Type):</label>
                    <input type="text" name="order_type" id="order_type" class="form-control" value="billpayment" required>
                </div>
                <div class="mb-3">
                    <label for="language" class="form-label">Ngôn ngữ (Language):</label>
                    <select name="language" id="language" class="form-select">
                        <option value="vn">Tiếng Việt</option>
                        <option value="en">English</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="bank_code" class="form-label">Ngân hàng (Bank Code):</label>
                    <select name="bank_code" id="bank_code" class="form-select">
                        <option value="">Không chọn</option>
                        <option value="NCB">Ngân hàng NCB</option>
                        <option value="VCB">Ngân hàng Vietcombank</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="payment_method" class="form-label">Phương thức thanh toán (Payment Method):</label>
                    <select name="payment_method" id="payment_method" class="form-select" required>
                        <option value="vnpay">VNPay</option>
                        <option value="zalopay">ZaloPay</option>
                        <option value="momo">Momo</option>
                    </select>
                </div>
                <button type="button" class="btn btn-secondary back-btn" data-prev="step-2">Quay lại</button>
                <button type="submit" class="btn btn-primary">Thanh Toán</button>
            </form>
        </div>

        <!-- Giai đoạn 4: Hoàn tất -->
        <div class="step" id="step-4" style="display:none;">
            <h3>Đặt vé thành công!</h3>
            <p>Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi!</p>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="ageModal" tabindex="-1" aria-labelledby="ageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ageModalLabel">Nhập ngày sinh</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p id="modalMessage"></p>
                    <input type="date" class="form-control" id="birthdateInput" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="confirmAge">Xác nhận</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="voucherModal" tabindex="-1" aria-labelledby="voucherModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="voucherModalLabel">Chọn khuyến mãi</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <ul class="list-group" id="voucher-list">
            <!-- Danh sách voucher sẽ được thêm vào đây -->
          </ul>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection
