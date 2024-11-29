@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Thông tin vé</h3>
    <table class="table table-bordered">
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
            <th>Khách hàng</th>
            <td>{{ $ticket->customer->customer_name }}</td>
        </tr>
        <tr>
            <th>Số giấy tờ</th>
            <td>{{ $ticket->customer->citizen_id }}</td>
        </tr>
    </table>
    <a href="{{ route('check-ticket.form') }}" class="btn btn-secondary">Quay lại</a>
</div>
@endsection