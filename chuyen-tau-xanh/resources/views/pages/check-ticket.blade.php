@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="text-primary text-center mb-4">KIỂM TRA VÉ</h2>
        <div class="alert alert-info">
            <p>Vui lòng điền đầy đủ thông tin để kiểm tra vé của bạn. Nếu bạn gặp vấn đề về vé, vui lòng liên hệ với chúng
                tôi.</p>
        </div>

        <form action="{{ route('check-ticket.process') }}" method="POST">
            @csrf
            <div class="card shadow-sm p-4">
                <p><strong>Vui lòng nhập các thông tin dưới đây để kiểm tra vé:</strong></p>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="ticket_id" class="form-label">Mã vé <span class="text-danger">*</span></label>
                            <input type="text" name="ticket_id" id="ticket_id" class="form-control"
                                placeholder="Nhập mã vé" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="train_mark" class="form-label">Mã tàu <span class="text-danger">*</span></label>
                            <input type="text" name="train_mark" id="train_mark" class="form-control"
                                placeholder="VD: SE1, TN1" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="day_start" class="form-label">Ngày đi <span class="text-danger">*</span></label>
                            <input type="date" name="day_start" id="day_start" class="form-control" required>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="station_start" class="form-label">Ga đi <span class="text-danger">*</span></label>
                            <input type="text" name="station_start" id="station_start" class="form-control"
                                placeholder="Nhập ga đi" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="station_end" class="form-label">Ga đến <span class="text-danger">*</span></label>
                            <input type="text" name="station_end" id="station_end" class="form-control"
                                placeholder="Nhập ga đến" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="citizen_id" class="form-label">Số giấy tờ <span class="text-danger">*</span></label>
                            <input type="text" name="citizen_id" id="citizen_id" class="form-control"
                                placeholder="Nhập giấy tờ" required>
                        </div>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger mt-4">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary px-4">Kiểm tra vé</button>
                </div>
            </div>
        </form>
    </div>
@endsection
