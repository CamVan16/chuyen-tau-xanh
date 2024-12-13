@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="text-primary text-center mb-4">THÔNG TIN VÉ</h2>

        <div class="alert alert-info">
            <p>Thông tin chi tiết vé của bạn. Nếu có bất kỳ vấn đề gì, vui lòng liên hệ với chúng tôi để được hỗ trợ.</p>
        </div>

        <div class="card shadow-sm p-4">
            <table class="table table-striped table-hover">
                <tbody>
                    <tr>
                        <th>Mã vé</th>
                        <td>{{ $ticket->id }}</td>
                    </tr>
                    <tr>
                        <th>Mã tàu</th>
                        <td>{{ $ticket->schedule->train_mark }}</td>
                    </tr>
                    <tr>
                        <th>Ga đi</th>
                        <td>{{ $ticket->schedule->station_start }}</td>
                    </tr>
                    <tr>
                        <th>Ga đến</th>
                        <td>{{ $ticket->schedule->station_end }}</td>
                    </tr>
                    <tr>
                        <th>Ngày đi</th>
                        <td>{{ $ticket->schedule->day_start }}</td>
                    </tr>
                    <tr>
                        <th>Giờ đi</th>
                        <td>{{ $ticket->schedule->time_start }}</td>
                    </tr>
                    <tr>
                        <th>Ngày đến</th>
                        <td>{{ $ticket->schedule->day_end }}</td>
                    </tr>
                    <tr>
                        <th>Giờ đến</th>
                        <td>{{ $ticket->schedule->time_end }}</td>
                    </tr>
                    <tr>
                        <th>Số ghế</th>
                        <td>{{ $ticket->schedule->seat_number }}</td>
                    </tr>
                    <tr>
                        <th>Tên toa</th>
                        <td>{{ $ticket->schedule->car_name }}</td>
                    </tr>
                    <tr>
                        <th>Khách hàng</th>
                        <td>{{ $ticket->customer->customer_name }}</td>
                    </tr>
                    <tr>
                        <th>Số giấy tờ</th>
                        <td>{{ $ticket->customer->citizen_id }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('check-ticket.form') }}" class="btn btn-primary px-4">Quay lại</a>
        </div>
    </div>
@endsection
