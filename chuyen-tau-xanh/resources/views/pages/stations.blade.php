@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Thông tin hành trình</h3>
        <form>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="gaDi">Ga đi</label>
                    <input type="text" id="gaDi" class="form-control" placeholder="Nhập hoặc chọn ga đi...">
                </div>
                <div class="col-md-4">
                    <label for="gaDen">Ga đến</label>
                    <input type="text" id="gaDen" class="form-control" placeholder="Nhập hoặc chọn ga đến...">
                </div>
                <div class="col-md-4">
                    <label for="ngay">Ngày đi</label>
                    <input type="date" id="ngay" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Tìm kiếm</button>
            </div>
        </form>
        <div id="stationList" class="row mt-3">
            <!-- Danh sách ga hiển thị tại đây -->
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $.ajax({
                url: '/api/station-areas',
                method: 'GET',
                success: function(data) {
                    let stationList = '';
                    data.forEach(station => {
                        stationList += `<div class="col-md-3 mb-2">
                            <button class="btn btn-outline-secondary w-100">${station.station_name}</button>
                        </div>`;
                    });
                    $('#stationList').html(stationList);
                }
            });
        });
    </script>
@endsection
