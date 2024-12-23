@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="text-primary mb-4">DANH SÁCH CÁC CHƯƠNG TRÌNH KHUYẾN MÃI</h2>

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
                            <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#voucherModal"
                                data-id="{{ $voucher->id }}">Xem</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="voucherModal" tabindex="-1" role="dialog" aria-labelledby="voucherModalLabel"
        aria-hidden="true">
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

    <hr>

    {{-- Qui định --}}
    <div class="container mt-4">
        <h2 class="text-primary">QUY ĐỊNH SỬ DỤNG DỊCH VỤ</h2>
        <div class="alert alert-info mt-4">
            <strong>Chú ý:</strong> Bạn cần đọc kỹ các quy định dưới đây trước khi sử dụng dịch vụ.
        </div>

        <div class="card mt-4">
            <div id="accordion">
                <!-- 1. Điều kiện sử dụng vé -->
                <div class="card">
                    <div class="card-header" id="headingOne">
                        <h5 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                1. Điều kiện sử dụng vé
                            </button>
                        </h5>
                    </div>
                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body">
                            - Vé mua qua hệ thống của chúng tôi chỉ có hiệu lực khi được thanh toán đầy đủ. <br />
                            - Vé chỉ có giá trị khi được xuất trình tại thời điểm lên tàu, vé không thể chuyển nhượng cho
                            người
                            khác. Hành khách phải có mặt tại ga tàu ít nhất 30 phút trước giờ khởi hành.
                        </div>
                    </div>
                </div>

                <!-- 2. Chính sách đặt vé -->
                <div class="card">
                    <div class="card-header" id="headingTwo">
                        <h5 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                2. Chính sách đặt vé
                            </button>
                        </h5>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                        <div class="card-body">
                            - Quý khách có thể đặt chỗ cho tối đa 10 khách (không bao gồm trẻ sơ sinh) trong mỗi lần thực
                            hiện. <br />
                            - Trẻ em dưới 10 tuổi tại thời điểm khởi hành phải được đặt chỗ đi cùng người lớn.<br />
                            - Trẻ em dưới 6 tuổi: Miễn vé và sử dụng chung chỗ của người lớn đi kèm.<br />
                            - Trẻ em từ 6 đến dưới 10 tuổi: Giảm 25% giá vé<br />
                            - Người cao tuổi từ 60 tuổi trở lên: Giảm 15% giá vé.<br />
                            - Sinh viên: Giảm 10% giá vé.<br />
                            - Giá vé trên Website chỉ áp dụng cho các giao dịch mua vé trên Website tại thời điểm mua
                            vé.<br />
                            - Đặt chỗ của Quý khách sẽ không được đảm bảo đến khi thanh toán thành công.
                        </div>
                    </div>
                </div>

                <!-- 3. Chính sách hoàn vé -->
                <div class="card">
                    <div class="card-header" id="headingThree">
                        <h5 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                3. Chính sách hoàn vé
                            </button>
                        </h5>
                    </div>
                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                        <div class="card-body">
                            - Hành khách có quyền trả lại vé, đổi vé trước giờ tàu chạy. Doanh nghiệp quy định cụ thể mức
                            khấu trừ tương ứng
                            với việc trả lại vé, đổi vé và các nội dung khác có liên quan đến việc trả lại vé, đổi vé của
                            hành khách. <br />
                            - Quý khách cần thực hiện yêu cầu hoàn vé qua hệ thống trong vòng 48 giờ trước giờ khởi hành.
                            Mọi yêu cầu hoàn
                            vé sẽ được xử lý trong vòng 3-5 ngày làm việc.
                        </div>
                    </div>
                </div>

                <!-- 4. Chính sách bảo mật thông tin -->
                <div class="card">
                    <div class="card-header" id="headingFour">
                        <h5 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                4. Chính sách bảo mật thông tin
                            </button>
                        </h5>
                    </div>
                    <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
                        <div class="card-body">
                            Chúng tôi cam kết bảo mật thông tin cá nhân của khách hàng và không chia sẻ với bất kỳ bên thứ
                            ba nào ngoài phạm
                            vi của dịch vụ. Mọi thông tin thu thập chỉ được sử dụng để cung cấp dịch vụ cho khách hàng.
                        </div>
                    </div>
                </div>

                <!-- 5. Quy định về hành lý -->
                <div class="card">
                    <div class="card-header" id="headingFive">
                        <h5 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                5. Quy định về hành lý
                            </button>
                        </h5>
                    </div>
                    <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion">
                        <div class="card-body">
                            Khách hàng có thể mang theo hành lý cá nhân khi sử dụng dịch vụ. Tuy nhiên, không được phép mang
                            các vật phẩm
                            nguy hiểm, dễ cháy nổ, hoặc các vật dụng vi phạm pháp luật. Đối với hành lý quá cỡ, vui lòng
                            tham khảo thêm quy
                            định tại quầy hỗ trợ.
                        </div>
                    </div>
                </div>

                <!-- 6. Điều khoản hủy lịch trình -->
                <div class="card">
                    <div class="card-header" id="headingSix">
                        <h5 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                data-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                6. Điều khoản hủy lịch trình
                            </button>
                        </h5>
                    </div>
                    <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordion">
                        <div class="card-body">
                            Trong trường hợp có sự cố hoặc bất khả kháng, công ty có quyền hủy lịch trình hoặc thay đổi giờ
                            khởi hành. Chúng
                            tôi sẽ thông báo đến khách hàng càng sớm càng tốt và hỗ trợ đổi lịch trình hoặc hoàn vé tùy vào
                            tình hình.
                        </div>
                    </div>
                </div>

                <!-- 7. Các điều khoản khác -->
                <div class="card">
                    <div class="card-header" id="headingSeven">
                        <h5 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                data-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                                7. Các điều khoản khác
                            </button>
                        </h5>
                    </div>
                    <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven" data-parent="#accordion">
                        <div class="card-body">
                            Chúng tôi có quyền thay đổi các quy định và điều khoản sử dụng dịch vụ mà không thông báo trước.
                            Mọi thay đổi sẽ
                            được công khai trên website chính thức của chúng tôi.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <a href="/" class="btn btn-primary">Quay lại trang chủ</a>
        </div>
    </div>

    <style>
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

@section('scripts')
    <script>
        $('#voucherModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Lấy nút bấm liên quan
            var voucherId = parseInt(button.data('id'), 10); // Lấy voucherId từ data-id của nút
            console.log('Voucher ID:', voucherId); // Kiểm tra voucherId

            $.ajax({
                url: '/api/vouchers/' + voucherId,
                method: 'GET',
                success: function(data) {
                    console.log("Dữ liệu trả về từ API:", data); // Kiểm tra dữ liệu trả về
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
