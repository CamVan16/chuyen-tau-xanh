@extends(backpack_view('blank'))

@section('content')
    <div class="container">
        <div class="row">
            <!-- Tổng số khách hàng -->
            <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <h5 class="card-title text-uppercase mb-1">Tổng số khách hàng</h5>
                                <h2 class="fw-bold mb-2">{{ $totalCustomers }}</h2>
                                <p class="text-muted small">{{ 1000 - $totalCustomers }} nữa đến cột mốc tiếp theo.</p>
                            </div>
                            <div class="ms-auto">
                                <i class="bi bi-people text-success fs-1"></i>
                            </div>
                        </div>
                        <div class="progress mt-2" style="height: 5px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $totalCustomers/1000 }}%;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tổng số bookings -->
            <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <h5 class="card-title text-uppercase mb-1">Tổng số bookings</h5>
                                <h2 class="fw-bold mb-2">{{ $totalBookings }}</h2>
                                <p class="text-muted small">Great! Đừng dừng lại.</p>
                            </div>
                            <div class="ms-auto">
                                <i class="bi bi-calendar-check text-danger fs-1"></i>
                            </div>
                        </div>
                        <div class="progress mt-2" style="height: 5px;">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $totalBookings/1000 }}%;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tổng số vé -->
            <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <h5 class="card-title text-uppercase mb-1">Tổng số vé</h5>
                                <h2 class="fw-bold mb-2">{{ $totalTickets }}</h2>
                                <p class="text-muted small">Xuất vé mỗi 3-4 ngày.</p>
                            </div>
                            <div class="ms-auto">
                                <i class="bi bi-ticket text-primary fs-1"></i>
                            </div>
                        </div>
                        <div class="progress mt-2" style="height: 5px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $totalTickets/1000 }}%;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tổng số hoàn tiền -->
            <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <h5 class="card-title text-uppercase mb-1">Tổng số hoàn vé</h5>
                                <h2 class="fw-bold mb-2">{{ $totalRefunds }}</h2>
                                <p class="text-muted small">Giữ dưới 100 khoản hoàn tiền.</p>
                            </div>
                            <div class="ms-auto">
                                <i class="bi bi-cash text-warning fs-1"></i>
                            </div>
                        </div>
                        <div class="progress mt-2" style="height: 5px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $totalRefunds/100 }}%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Doanh thu -->
        <div class="row">
            <!-- Doanh thu hôm nay -->
            <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body">
                        <h5 class="card-title text-uppercase">Doanh thu hôm nay</h5>
                        <h2 class="fw-bold text-success">{{ number_format($todayRevenue) }} VND</h2>
                        <i class="bi bi-graph-up-arrow text-success fs-2"></i>
                    </div>
                </div>
            </div>

            <!-- Doanh thu 30 ngày -->
            <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body">
                        <h5 class="card-title text-uppercase">Doanh thu 30 ngày</h5>
                        <h2 class="fw-bold text-primary">{{ number_format($totalRevenue30Days) }} VND</h2>
                        <i class="bi bi-calendar-range text-primary fs-2"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Biểu đồ doanh thu 30 ngày -->
        <div class="col-lg-12 col-md-12 col-sm-12 mt-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-uppercase">Biểu đồ doanh thu 30 ngày</h5>
                    <div class="chart-container" style="flex-grow: 1; max-height: 500px;">
                        {!! $chartRevenue->container() !!}
                    </div>
                </div>
            </div>
        </div>

        <!-- Biểu đồ khách hàng -->
        <div class="row mt-4">
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-uppercase">Tổng quan khách hàng</h5>
                        <div class="chart-container" style="max-height: 400px;">
                            {!! $chartCustomers->container() !!}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Biểu đồ đặt vé -->
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-uppercase">Tổng quan đặt vé</h5>
                        <div class="chart-container" style="max-height: 400px;">
                            {!! $chartBookings->container() !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-uppercase">Tổng quan vé</h5>
                        <div class="chart-container" style="max-height: 400px;">
                            {!! $chartTickets->container() !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-uppercase">Tổng quan hoàn vé</h5>
                        <div class="chart-container" style="max-height: 400px;">
                            {!! $chartRefunds->container() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    {!! $chartCustomers->script() !!}
    {!! $chartBookings->script() !!}
    {!! $chartTickets->script() !!}
    {!! $chartRefunds->script() !!}
    {!! $chartRevenue->script() !!}
@endsection
