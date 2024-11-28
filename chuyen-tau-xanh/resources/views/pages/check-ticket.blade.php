@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Kiểm tra vé</h3>
    <form action="{{ route('check-ticket.process') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-4">
                <label for="ticket_id">Mã vé</label>
                <input type="text" name="ticket_id" id="ticket_id" class="form-control" placeholder="Nhập mã vé" required>
            </div>
            <div class="col-md-4">
                <label for="train_mark">Mã tàu</label>
                <input type="text" name="train_mark" id="train_mark" class="form-control" placeholder="VD: SE1, TN1">
            </div>
            <div class="col-md-4">
                <label for="day_start">Ngày đi</label>
                <input type="date" name="day_start" id="day_start" class="form-control">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-4">
                <label for="station_start">Ga đi</label>
                <input type="text" name="station_start" id="station_start" class="form-control" placeholder="Nhập ga đi">
            </div>
            <div class="col-md-4">
                <label for="station_end">Ga đến</label>
                <input type="text" name="station_end" id="station_end" class="form-control" placeholder="Nhập ga đến">
            </div>
            <div class="col-md-4">
                <label for="citizen_id">Số giấy tờ</label>
                <input type="text" name="citizen_id" id="citizen_id" class="form-control" placeholder="Nhập giấy tờ">
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-4">Kiểm tra vé</button>
    </form>

    @if ($errors->any())
        <div class="alert alert-danger mt-3">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
@endsection
