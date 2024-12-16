@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12 col-md-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="text-center text-success mb-4">Đổi vé thành công!</h2>

                    <div class="row">
                        <div class="col-12">
                            <h4 class="mb-3"><strong>Thông tin vé mới:</strong></h4>
                            <ul class="list-group">
                                <li class="list-group-item"><strong>Mã tàu:</strong> {{ $newTicket->schedule->train_id }}</li>
                                <li class="list-group-item"><strong>Mác tàu:</strong> {{ $newTicket->schedule->train_mark }}</li>
                                <li class="list-group-item"><strong>Toa:</strong> {{ $newTicket->schedule->car_name }}</li>
                                <li class="list-group-item"><strong>Ghế:</strong> {{ $newTicket->schedule->seat_number }}</li>
                                <li class="list-group-item"><strong>Giá:</strong> {{ number_format($newTicket->price, 0, ',', '.') }} VND</li>
                            </ul>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <h4 class="mb-3"><strong>Thông tin đổi vé:</strong></h4>
                            <ul class="list-group">
                                <li class="list-group-item"><strong>Mã vé cũ:</strong> {{ $exchange->old_ticket_id }}</li>
                                <li class="list-group-item"><strong>Mã đặt vé mới:</strong> {{ $exchange->newBookingId }}</li>
                                <li class="list-group-item"><strong>Mã vé mới:</strong> {{ $exchange->new_ticket_id }}</li>
                                <li class="list-group-item"><strong>Tiền thừa:</strong> {{ number_format(-$exchange->additional_price, 0, ',', '.') }} VND</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Thông tin hoàn tiền -->
                    <div class="mt-4">
                        <p class="text-muted text-center">
                            <em>Tiền hoàn lại sẽ được chuyển về tài khoản của bạn trong vòng 3-5 ngày làm việc.</em>
                        </p>
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ url('/') }}" class="btn btn-primary btn-lg">Trở về trang chủ</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
