{{-- @extends('layouts.app')

@section('content') --}}
{{-- <div class="container mt-4">
    <div class="d-flex justify-content-between mb-4">
        <div class="step text-center">
            <span class="badge badge-primary">1</span>
            <p>Chọn vé đổi</p>
        </div>
        <div class="step text-center">
            <span class="badge badge-primary">2</span>
            <p>Tìm vé đổi</p>
        </div>
        <div class="step text-center">
            <span class="badge badge-primary">3</span>
            <p>Xác nhận</p>
        </div>
        <div class="step text-center">
            <span class="badge badge-secondary">4</span>
            <p>Hoàn tất</p>
        </div>
    </div>

    <h2 class="text-primary">XÁC NHẬN MÃ ĐỔI VÉ</h2>
    <p>Vui lòng nhập mã xác nhận để tiếp tục quá trình đổi vé.</p>

    <form action="{{ route('exchange.verifyConfirmation') }}" method="POST" class="mt-4">
        @csrf
        <div class="form-group">
            <label for="confirmation_code">Mã xác nhận:</label>
            <input
                type="text"
                id="confirmation_code"
                name="confirmation_code"
                class="form-control"
                required
                minlength="6"
                maxlength="8"
                placeholder="Nhập mã xác nhận">
        </div>
        @error('error')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
        @enderror
        <button type="submit" class="btn btn-primary mt-2">Xác nhận</button>
    </form>
</div>
<style>
    .step {
        flex: 1;
    }
    .step .badge {
        font-size: 1.2em;
        padding: 10px;
    }
    .step p {
        margin-top: 10px;
    }
    .card {
        border: 1px solid #ddd;
        border-radius: 5px;
    }
    .alert-success {
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
        padding: 15px;
        border-radius: 5px;
    }
    .alert-success i {
        margin-right: 10px;
    }
</style> --}}
@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h3>Vé muốn đổi</h3>
    <div id="currentTicketContainer" class="mb-4"></div>

    <h3>Vé mới</h3>
    <div id="swapTicketContainer" class="mb-4"></div>

    <button id="confirmSwap" class="btn btn-success">Xác nhận đổi vé</button>
</div>
<script>
    $(document).ready(function() {
        // Lấy vé muốn đổi từ sessionStorage (vé cũ)
        const currentTicket = JSON.parse(sessionStorage.getItem('currentTicket'));
        if (currentTicket) {
            const $currentTicketItem = $(`
                <div class="ticket-item">
                    <div class="train-info">
                        <strong>${currentTicket.train_mark}</strong> ${currentTicket.from_station} → ${currentTicket.to_station}
                        <p><strong>Khởi hành:</strong> ${currentTicket.departure_date} lúc ${currentTicket.departure_time}</p>
                        <p>Toa: ${currentTicket.car} | Ghế: ${currentTicket.seat_index} | ${currentTicket.seat_type}</p>
                    </div>
                    <div class="ticket-footer">
                        <p class="ticket-price text-success"><strong>${(currentTicket.price).toLocaleString()} VNĐ</strong></p>
                    </div>
                </div>
            `);
            $('#currentTicketContainer').append($currentTicketItem);
        }

        // Lấy vé đổi từ localStorage (vé mới)
        const tickets = JSON.parse(localStorage.getItem('ticket-pocket')) || [];
        if (tickets.length > 0) {
            const swapTicket = tickets[tickets.length - 1]; // Giữ lại vé mới nhất
            const $swapTicketItem = $(`
                <div class="ticket-item">
                    <div class="train-info">
                        <strong>${swapTicket.train_mark}</strong> ${swapTicket.from_station} → ${swapTicket.to_station}
                        <p><strong>Khởi hành:</strong> ${swapTicket.departure_date} lúc ${swapTicket.departure_time}</p>
                        <p>Toa: ${swapTicket.car} | Ghế: ${swapTicket.seat_index} | ${swapTicket.seat_type}</p>
                    </div>
                    <div class="ticket-footer">
                        <p class="ticket-price text-success"><strong>${(swapTicket.price).toLocaleString()} VNĐ</strong></p>
                    </div>
                </div>
            `);
            $('#swapTicketContainer').append($swapTicketItem);
        }

        // Xác nhận đổi vé
        $('#confirmSwap').on('click', function() {
            // Xử lý đổi vé ở đây (gọi API hoặc lưu thông tin vào localStorage/Database)
            alert('Vé đã được đổi!');
        });
    });
</script>
@endsection
