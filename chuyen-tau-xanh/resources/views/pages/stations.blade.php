@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="text-primary text-center mb-4">THÔNG TIN HÀNH TRÌNH</h2>

        <div class="alert alert-info">
            <p>Quý khách vui lòng nhập thông tin về ga đi, ga đến và ngày đi để tìm kiếm hành trình.</p>
        </div>

        <form>
            <div class="row mb-4">
                <div class="col-md-4">
                    <label for="gaDi" class="form-label">Ga đi <span class="text-danger">*</span></label>
                    <input type="text" id="gaDi" class="form-control" placeholder="Nhập hoặc chọn ga đi...">
                    <div id="gaDiSuggestions" class="list-group mt-2" style="max-height: 200px; overflow-y: auto;"></div>
                </div>
                <div class="col-md-4">
                    <label for="gaDen" class="form-label">Ga đến <span class="text-danger">*</span></label>
                    <input type="text" id="gaDen" class="form-control" placeholder="Nhập hoặc chọn ga đến..."
                        disabled>
                    <div id="gaDenSuggestions" class="list-group mt-2" style="max-height: 200px; overflow-y: auto;"></div>
                </div>
                <div class="col-md-4">
                    <label for="ngay" class="form-label">Ngày đi <span class="text-danger">*</span></label>
                    <input type="date" id="ngay" class="form-control" disabled>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary" id="searchBtn" disabled>Tìm kiếm</button>
            </div>
        </form>

        <div id="stationList" class="row mt-4">
            <!-- Danh sách ga hiển thị tại đây -->
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            let stations = [];
            let selectedGaDi = '';
            let selectedGaDen = '';
            let activeInput = ''; // Xác định trường đang được chọn: gaDi hoặc gaDen

            // Lấy danh sách các ga
            $.ajax({
                url: '/api/station-areas',
                method: 'GET',
                success: function(data) {
                    stations = data;
                    renderStationList(data);
                }
            });

            // Hiển thị danh sách ga
            function renderStationList(data) {
                let stationList = '';
                data.forEach(station => {
                    stationList += `<div class="col-md-3 mb-2">
                    <button class="btn btn-outline-secondary w-100 station-btn" data-name="${station.station_name}">${station.station_name}</button>
                </div>`;
                });
                $('#stationList').html(stationList);
            }

            // Gán trường đang được chọn
            $('#gaDi').on('focus', function() {
                activeInput = 'gaDi';
                $('#gaDiSuggestions').empty();
            });

            $('#gaDen').on('focus', function() {
                activeInput = 'gaDen';
                $('#gaDenSuggestions').empty();
            });

            // Tìm kiếm ga
            $('#gaDi, #gaDen').on('input', function() {
                const searchQuery = $(this).val().toLowerCase();
                const filteredStations = stations.filter(station =>
                    station.station_name.toLowerCase().includes(searchQuery) &&
                    (activeInput === 'gaDi' || station.station_name !== selectedGaDi)
                );
                const suggestionBox = activeInput === 'gaDi' ? '#gaDiSuggestions' : '#gaDenSuggestions';
                renderSuggestions(filteredStations, suggestionBox, activeInput);
            });

            // Hiển thị gợi ý tìm kiếm
            function renderSuggestions(data, suggestionBoxId, type) {
                const suggestions = data.map(station =>
                    `<button class="list-group-item list-group-item-action suggestion-item" data-name="${station.station_name}" data-type="${type}">
                    ${station.station_name}
                </button>`
                ).join('');
                $(suggestionBoxId).html(suggestions).show();
            }

            // Xử lý chọn ga từ danh sách gợi ý hoặc danh sách chính
            $(document).on('click', '.suggestion-item, .station-btn', function() {
                const selectedStation = $(this).data('name');
                const type = activeInput; // Chọn ga dựa trên trường đang được active

                if (type === 'gaDi') {
                    selectedGaDi = selectedStation;
                    $('#gaDi').val(selectedGaDi);
                    $('#gaDiSuggestions').empty().hide();
                    $('#gaDen').prop('disabled', false).val('');
                    $('#gaDenSuggestions').empty();
                } else if (type === 'gaDen') {
                    selectedGaDen = selectedStation;
                    $('#gaDen').val(selectedGaDen);
                    $('#gaDenSuggestions').empty().hide();
                    $('#ngay').prop('disabled', false);
                    $('#searchBtn').prop('disabled', false);
                }
            });

            // Chọn ga trực tiếp từ danh sách dưới
            $(document).on('click', '.station-btn', function() {
                const selectedStation = $(this).data('name');
                if (activeInput === 'gaDi') {
                    selectedGaDi = selectedStation;
                    $('#gaDi').val(selectedGaDi);
                    $('#gaDen').prop('disabled', false).val('');
                    $('#ngay').prop('disabled', true);
                    selectedGaDen = '';
                } else if (activeInput === 'gaDen') {
                    selectedGaDen = selectedStation;
                    $('#gaDen').val(selectedGaDen);
                    $('#ngay').prop('disabled', false);
                    $('#searchBtn').prop('disabled', false);
                }
            });

            // Xử lý tìm kiếm
            $('form').on('submit', function(event) {
                event.preventDefault();
                const gaDi = $('#gaDi').val();
                const gaDen = $('#gaDen').val();
                const ngay = $('#ngay').val();

                window.location.href = `/trains/results?gaDi=${gaDi}&gaDen=${gaDen}&ngay=${ngay}`;
            });
        });
    </script>
@endsection
@push('styles')
    <style>
        /* Tạo hiệu ứng hover cho card */
        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.1);
        }

        /* Các nút chọn ga */
        .station-btn {
            background-color: #f8f9fa;
            border-color: #ddd;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        .station-btn:hover {
            background-color: #e9ecef;
            border-color: #ccc;
        }

        /* Gợi ý danh sách */
        .list-group-item {
            cursor: pointer;
        }

        /* Nút tìm kiếm */
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        /* Phần nhập liệu */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
        }
    </style>
@endpush
