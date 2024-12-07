@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center mb-4">Danh Sách Khuyến Mãi</h1>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Mã Voucher</th>
                <th>Tên Voucher</th>
                <th>Giảm Giá</th>
                <th>Áp Dụng Cho Đơn Hàng Từ</th>
                <th>Ngày Áp Dụng</th>
                <th>Hết Hạn</th>
                <th>Chi Tiết</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($vouchers as $voucher)
                <tr>
                    <td>{{ $voucher->code }}</td>
                    <td>{{ $voucher->name }}</td>
                    <td>{{ $voucher->percent }}%</td>
                    <td>{{ number_format($voucher->min_price_order) }} VND</td>
                    <td>{{ \Carbon\Carbon::parse($voucher->from_date)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($voucher->to_date)->format('d/m/Y') }}</td>
                    <td>
                        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#voucherModal" data-id="{{ $voucher->id }}">Xem</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="modal fade" id="voucherModal" tabindex="-1" role="dialog" aria-labelledby="voucherModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="voucherModalLabel">Chi Tiết Voucher</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Mã Voucher:</strong> <span id="voucherCode"></span></p>
                <p><strong>Tên Voucher:</strong> <span id="voucherName"></span></p>
                <p><strong>Giảm Giá:</strong> <span id="voucherPercent"></span>%</p>
                <p><strong>Áp Dụng Cho Đơn Hàng Từ:</strong> <span id="voucherMinPriceOrder"></span> VND</p>
                <p><strong>Ngày Áp Dụng:</strong> <span id="voucherFromDate"></span></p>
                <p><strong>Ngày Hết Hạn:</strong> <span id="voucherToDate"></span></p>
                <p><strong>Chi Tiết:</strong> <span id="voucherDescription"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $('#voucherModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); 
    var voucherId = button.data('id'); 

    $.ajax({
    url: '/api/vouchers/' + voucherId, 
    method: 'GET',
    success: function(data) {
        $('#voucherCode').text(data.code);
        $('#voucherName').text(data.name);
        $('#voucherPercent').text(data.percent);
        $('#voucherMinPriceOrder').text(data.min_price_order.toLocaleString());
        $('#voucherFromDate').text(data.from_date);
        $('#voucherToDate').text(data.to_date);
        $('#voucherDescription').text(data.description);
    },
    error: function(xhr, status, error) {
        console.log("Lỗi API: " + error);  
        alert('Có lỗi xảy ra khi lấy thông tin voucher.');
    }
});

});

</script>
@endsection
