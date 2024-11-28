@extends('layouts.app')

@section('title', 'Liên Hệ')

@section('content')
    <div class="container mt-5">
        <h2 class="text-primary text-center mb-4">LIÊN HỆ VỚI CHÚNG TÔI</h2>
        <div class="row">
            <div class="col-md-">
                <div class="card">
                    <div class="card-header text-primary">
                        <h4 class="mb-0">Thông Tin Liên Hệ</h4>
                    </div>
                    <div class="card-body">
                        <p><strong>Địa chỉ:</strong> Đường Hàn Thuyên, khu phố 6 P, Thủ Đức, Hồ Chí Minh</p>
                        <p><strong>Điện thoại:</strong> 1900 8198 </p>
                        <p><strong>Email:</strong> 7radiante@gmail.com </p>
                        <p><strong>Thời gian làm việc:</strong></p>
                        <ul>
                            <li>Thứ 2 - Thứ 6: 8:00 - 18:00</li>
                            <li>Thứ 7, Chủ nhật: 8:00 - 12:00</li>
                        </ul>
                    </div>
                </div>
                <div class="col-12 mt-4">
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15060.701329810783!2d106.8030541!3d10.8700089!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x317527587e9ad5bf%3A0xafa66f9c8be3c91!2zVHLGsOG7nW5nIMSQ4bqhaSBo4buNYyBDw7RuZyBuZ2jhu4cgVGjDtG5nIHRpbiAtIMSQSFFHIFRQLkhDTQ!5e1!3m2!1svi!2s!4v1732767498707!5m2!1svi!2s"
                            width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-primary">
                        <h4 class="mb-0">Gửi Thông Tin Liên Hệ</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('contact.submit') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="name">Họ và Tên</label>
                                <input type="text" id="name" name="name" class="form-control"
                                    placeholder="Nhập họ và tên" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" class="form-control"
                                    placeholder="Nhập email của bạn" required>
                            </div>
                            <div class="form-group">
                                <label for="message">Nội Dung</label>
                                <textarea id="message" name="message" class="form-control" rows="5" placeholder="Nhập nội dung liên hệ"
                                    required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Gửi Liên Hệ</button>
                            @if (session('success'))
                                <div class="alert alert-success mt-4">
                                    {{ session('success') }}
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .form-group label {
            font-weight: bold;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>
@endsection
