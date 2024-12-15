@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Thông tin hành trình: Tuyến</h3>
    <p><strong>Ga đi:</strong> {{ $gaDi }}</p>
    <p><strong>Ga đến:</strong> {{ $gaDen }}</p>
    <p><strong>Ngày:</strong> {{ $ngay }}</p>

    @if ($trains->isEmpty())
        <p>Không tìm thấy chuyến tàu nào phù hợp.</p>
    @else
        <div class="row">
            @foreach ($trains as $train)
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">
                                🚆 {{ $train->train_mark }}
                            </h5>
                            <p><strong>Ga đi:</strong> {{ $train->ga_di }} ({{ $train->gio_di }})</p>
                            <p><strong>Ga đến:</strong> {{ $train->ga_den }} ({{ $train->gio_den }})</p>
                            <p><strong>Thời gian hành trình:</strong> {{ $train->thoi_gian_hanh_trinh }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
