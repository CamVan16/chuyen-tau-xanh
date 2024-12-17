@extends('layouts.app')

@section('title', 'Tìm vé')

@section('content')
<div class="row container">
    <div class="col-xs-12 col-sm-4 col-md-3">
        <form action="{{ route('routes.search') }}" method="POST">
            @csrf
            <label for="stationA">Ga Đi:</label>
            <div class="dropdown">
                <input type="text" name="stationA" id="stationA" class="form-control" autocomplete="off" required>
                <ul class="dropdown-menu" id="stationA-dropdown" style="width: 30%;"></ul>
            </div>

            <label for="stationB">Ga Đến:</label>
            <div class="dropdown">
                <input type="text" name="stationB" id="stationB" class="form-control" autocomplete="off" required>
                <ul class="dropdown-menu" id="stationB-dropdown" style="width: 30%;"></ul>
            </div>
            <!-- <label>Loại vé:</label> -->
            <label>
                <input type="radio" name="ticketType" value="one-way" checked onclick="toggleReturnDate()"> Một chiều
            </label>
            <label>
                <input type="radio" name="ticketType" value="round-trip" onclick="toggleReturnDate()"> Khứ hồi
            </label>

            <label for="departureDate">Ngày Đi:</label>
            <input type="date" name="departureDate" id="departureDate" required>

            <label for="returnDate">Ngày Về:</label>
            <input type="date" name="returnDate" id="returnDate" disabled>
            <br>
            <button class="btn btn-primary mb-3 mt-3" type="submit">Tìm Kiếm</button>
        </form>
    </div>
    <div class="col-xs-12 col-sm-8 col-md-9 carousel slide" id="homepageCarousel" data-ride="carousel">
        <ol class="carousel-indicators">
            <li data-target="#homepageCarousel" data-slide-to="0" class="active"></li>
            <li data-target="#homepageCarousel" data-slide-to="1"></li>
            <li data-target="#homepageCarousel" data-slide-to="2"></li>
        </ol>

        <div class="carousel-inner">
            <div class="carousel-item active">
            <img src="{{ asset('banner03.jpg') }}" class="d-block w-100" alt="Slide 1">
            </div>
            <div class="carousel-item">
            <img src="{{ asset('banner04.jpg') }}" class="d-block w-100" alt="Slide 2">
            </div>
        </div>

        <a class="carousel-control-prev" href="#homepageCarousel" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#homepageCarousel" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
</div>
<div class="container mt-4">
    <h2 class="text-primary">HƯỚNG DẪN SỬ DỤNG DỊCH VỤ</h2>
    <div class="alert alert-info mt-4">
        <strong>Chào mừng bạn đến với hướng dẫn sử dụng dịch vụ của chúng tôi!</strong><br>
        Dưới đây là các bước chi tiết để bạn có thể sử dụng dịch vụ một cách hiệu quả nhất.
    </div>
    <div class="card mt-4">
        <div id="accordion">
            <!-- 1. Tìm kiếm vé -->
            <div class="card">
                <div class="card-header" id="headingOne">
                    <h5 class="mb-0">
                        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                            data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            1. Tìm kiếm vé
                        </button>
                    </h5>
                </div>
                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                    <div class="card-body">
                        Để bắt đầu, truy cập vào trang "Tìm Vé" trên website. Nhập thông tin điểm đi, điểm đến, và ngày
                        giờ khởi hành. Sau đó, hệ thống sẽ tự động hiển thị các chuyến tàu phù hợp với yêu cầu của bạn.
                        Bạn có thể lựa chọn chuyến tàu phù hợp với lịch trình của mình và tiếp tục sang bước đặt vé.
                    </div>
                </div>
            </div>

            <!-- 2. Đặt vé -->
            <div class="card">
                <div class="card-header" id="headingTwo">
                    <h5 class="mb-0">
                        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                            data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            2. Đặt vé
                        </button>
                    </h5>
                </div>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                    <div class="card-body">
                        Sau khi lựa chọn chuyến tàu mong muốn, bạn sẽ cần điền đầy đủ thông tin hành khách. Các trường
                        thông tin cần nhập sẽ bao gồm tên, số điện thoại và các yêu cầu đặc biệt nếu có. Sau khi hoàn
                        tất, bạn có thể tiến hành thanh toán qua các phương thức thanh toán trực tuyến. Hãy chắc chắn
                        kiểm tra lại các thông tin hành khách và vé trước khi thanh toán.
                    </div>
                </div>
            </div>

            <!-- 3. Kiểm tra thông tin vé -->
            <div class="card">
                <div class="card-header" id="headingThree">
                    <h5 class="mb-0">
                        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                            data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            3. Kiểm tra thông tin vé
                        </button>
                    </h5>
                </div>
                <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                    <div class="card-body">
                        Sau khi hoàn tất việc đặt vé, bạn có thể kiểm tra lại thông tin vé của mình trong mục "Kiểm tra
                        vé". Tại đây, bạn sẽ thấy tất cả các chi tiết về hành trình, bao gồm giờ khởi hành, điểm đến,
                        thông tin hành khách và tình trạng vé của bạn. Nếu có sự thay đổi về vé hoặc hành trình, bạn sẽ
                        được thông báo qua email hoặc SMS.
                    </div>
                </div>
            </div>

            <!-- 4. Đổi hoặc hoàn vé -->
            <div class="card">
                <div class="card-header" id="headingFour">
                    <h5 class="mb-0">
                        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                            data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            4. Đổi hoặc hoàn vé
                        </button>
                    </h5>
                </div>
                <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
                    <div class="card-body">
                        Nếu bạn cần thay đổi thông tin vé hoặc hủy vé đã đặt, bạn có thể thực hiện các yêu cầu này thông
                        qua mục "Trả vé". Tại đây, bạn có thể yêu cầu đổi chuyến tàu, thay đổi giờ khởi hành hoặc hủy vé
                        hoàn toàn. Lưu ý rằng mọi yêu cầu thay đổi hay hủy vé sẽ tuân theo chính sách hoàn vé và phí hủy
                        vé của chúng tôi. Hãy kiểm tra kỹ trước khi quyết định.
                    </div>
                </div>
            </div>

            <!-- 5. Hỗ trợ khách hàng -->
            <div class="card">
                <div class="card-header" id="headingFive">
                    <h5 class="mb-0">
                        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                            data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                            5. Hỗ trợ khách hàng
                        </button>
                    </h5>
                </div>
                <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion">
                    <div class="card-body">
                        Nếu bạn gặp bất kỳ khó khăn nào trong quá trình sử dụng dịch vụ, đội ngũ hỗ trợ khách hàng của
                        chúng tôi luôn sẵn sàng trợ giúp. Bạn có thể liên hệ với chúng tôi qua các kênh hỗ trợ như
                        email, điện thoại, hoặc chat trực tuyến. Chúng tôi cam kết giải quyết mọi vấn đề của bạn một
                        cách nhanh chóng và hiệu quả.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="/" class="btn btn-primary">Quay lại trang chủ</a>
    </div>
</div>
    <script>
        $(document).ready(function () {
            $('#homepageCarousel').carousel({
            interval: 3000,
            pause: 'hover'
            });
        });
        document.addEventListener('DOMContentLoaded', async function () {
            const stations = await fetch('{{ route('stations.name') }}')
                .then(response => response.json());
            // console.log(stations);
            setupAutocomplete('stationA', 'stationA-dropdown', stations);
            setupAutocomplete('stationB', 'stationB-dropdown', stations);
        });
        function setupAutocomplete(inputId, dropdownId, stations) {
            const input = document.getElementById(inputId);
            const dropdown = document.getElementById(dropdownId);

            input.addEventListener('input', function () {
                const query = input.value.trim().toLowerCase();
                // console.log(query);
                dropdown.innerHTML = '';
                if (query.length > 0) {
                    const filteredStations = stations.filter(station => station.name.toLowerCase().includes(query));
                    filteredStations.forEach(station => {
                        const li = document.createElement('li');
                        li.textContent = station.name;
                        li.classList.add('dropdown-item');
                        li.onclick = () => {
                            input.value = station.name;
                            dropdown.innerHTML = '';
                        };
                        dropdown.appendChild(li);
                    });
                    dropdown.style.display = 'block';
                } else {
                    dropdown.style.display = 'none';
                }
            });

            document.addEventListener('click', function (e) {
                if (!dropdown.contains(e.target) && e.target !== input) {
                    dropdown.style.display = 'none';
                }
            });
        }
        function toggleReturnDate() {
            const returnDateInput = document.getElementById('returnDate');
            const isRoundTrip = document.querySelector('input[name="ticketType"]:checked').value === 'round-trip';
            returnDateInput.disabled = !isRoundTrip;
            returnDateInput.required = isRoundTrip;
        }
    </script>
    <style>
        label {
            display: block;
            margin-top: 10px;
        }
        .dropdown-menu {
            display: none;
            max-height: 200px;
            overflow-y: auto;
            position: absolute;
            z-index: 1000;
            background-color: #fff;
            border: 1px solid #ccc;
        }

        .dropdown-item {
            cursor: pointer;
            padding: 8px 12px;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
        }
        .collapse {
            background-color: transparent !important;
        }

        .card-body {
            padding-left: 30px;
            padding-right: 30px;
        }

        .collapse.show {
            background-color: transparent !important;
        }

        .card-header {
            background-color: transparent;
            border: none;
        }

        .btn-link {
            color: #007bff;
            text-decoration: none;
        }

        .btn-link:focus {
            outline: none;
            box-shadow: none;
        }
    </style>
@endsection
