@extends('layouts.app')

@section('content')
<div class="container mt-4">
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
</style>
@endsection
