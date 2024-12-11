@extends('layouts.app')

@section('title', 'Quy định sử dụng dịch vụ')

@section('content')
    <div class="container mt-4">
        <h2 class="text-primary">Quy định sử dụng dịch vụ</h2>
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

                <!-- 3. Chính sách đổi vé -->
                <div class="card">
                    <div class="card-header" id="headingThree">
                        <h5 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                3. Chính sách đổi vé
                            </button>
                        </h5>
                    </div>
                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                        <div class="card-body">
                            - Hành khách có quyền đổi vé trước giờ tàu. Doanh nghiệp quy định cụ thể mức khấu trừ tương ứng
                            với việc đổi vé và các nội dung khác có liên quan đến việc đổi vé của hành khách. <br />
                            - Quý khách cần thực hiện yêu cầu đổi vé qua hệ thống trong vòng 4 giờ trước giờ khởi hành. Mọi
                            yêu cầu hoàn vé sẽ được xử lý trong vòng 3-5 ngày làm việc. <br />
                            - Trong trường hợp đổi vé, nếu số tiền chênh lệch là dư thừa, khoản tiền sẽ được hoàn trả vào
                            tài khoản theo phương thức mà quý khách đã sử dụng để thanh toán. Nếu số tiền cần thanh toán
                            vượt quá giá trị ban đầu, quý khách cần thực hiện thanh toán phần chênh lệch để hoàn tất quy
                            trình đổi vé.
                        </div>
                    </div>
                </div>

                <!-- 4. Chính sách hoàn vé -->
                <div class="card">
                    <div class="card-header" id="headingFour">
                        <h5 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                4. Chính sách hoàn vé
                            </button>
                        </h5>
                    </div>
                    <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
                        <div class="card-body">
                            - Hành khách có quyền trả lại vé trước giờ tàu chạy. Doanh nghiệp quy định cụ thể mức
                            khấu trừ tương ứng
                            với việc trả lại vé và các nội dung khác có liên quan đến việc trả lại vé của
                            hành khách. <br />
                            - Quý khách cần thực hiện yêu cầu hoàn vé qua hệ thống trong vòng 4 giờ trước giờ khởi hành.
                            Mọi yêu cầu hoàn
                            vé sẽ được xử lý trong vòng 3-5 ngày làm việc.
                        </div>
                    </div>
                </div>

                <!-- 5. Chính sách bảo mật thông tin -->
                <div class="card">
                    <div class="card-header" id="headingFive">
                        <h5 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                5. Chính sách bảo mật thông tin
                            </button>
                        </h5>
                    </div>
                    <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion">
                        <div class="card-body">
                            Chúng tôi cam kết bảo mật thông tin cá nhân của khách hàng và không chia sẻ với bất kỳ bên thứ
                            ba nào ngoài phạm
                            vi của dịch vụ. Mọi thông tin thu thập chỉ được sử dụng để cung cấp dịch vụ cho khách hàng.
                        </div>
                    </div>
                </div>

                <!-- 6. Quy định về hành lý -->
                <div class="card">
                    <div class="card-header" id="headingSix">
                        <h5 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                data-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                6. Quy định về hành lý
                            </button>
                        </h5>
                    </div>
                    <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordion">
                        <div class="card-body">
                            Khách hàng có thể mang theo hành lý cá nhân khi sử dụng dịch vụ. Tuy nhiên, không được phép mang
                            các vật phẩm
                            nguy hiểm, dễ cháy nổ, hoặc các vật dụng vi phạm pháp luật. Đối với hành lý quá cỡ, vui lòng
                            tham khảo thêm quy
                            định tại quầy hỗ trợ.
                        </div>
                    </div>
                </div>

                <!-- 7. Điều khoản hủy lịch trình -->
                <div class="card">
                    <div class="card-header" id="headingSeven">
                        <h5 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                data-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                                6. Điều khoản hủy lịch trình
                            </button>
                        </h5>
                    </div>
                    <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven" data-parent="#accordion">
                        <div class="card-body">
                            Trong trường hợp có sự cố hoặc bất khả kháng, công ty có quyền hủy lịch trình hoặc thay đổi giờ
                            khởi hành. Chúng
                            tôi sẽ thông báo đến khách hàng càng sớm càng tốt và hỗ trợ đổi lịch trình hoặc hoàn vé tùy vào
                            tình hình.
                        </div>
                    </div>
                </div>

                <!-- 8. Các điều khoản khác -->
                <div class="card">
                    <div class="card-header" id="headingEight">
                        <h5 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                                data-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                                8. Các điều khoản khác
                            </button>
                        </h5>
                    </div>
                    <div id="collapseEight" class="collapse" aria-labelledby="headingEight" data-parent="#accordion">
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
