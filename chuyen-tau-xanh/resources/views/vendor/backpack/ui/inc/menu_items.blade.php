{{-- This file is used for menu items by any Backpack v6 theme --}}
<link href="{{ asset('css/admin-custom.css') }}" rel="stylesheet">
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i>
        {{ trans('backpack::base.dashboard') }}</a></li>

<li class="nav-item menu-group">
    <a class="nav-link" href="#" onclick="toggleMenu('booking-menu')"><i class="la la-ticket-alt nav-icon"></i> Quản
        lý vé tàu</a>
    <ul class="nav nav-treeview" id="booking-menu" style="display: none;">
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('customer') }}"><i
                    class="la la-users nav-icon"></i> Khách hàng</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('booking') }}"><i
                    class="la la-calendar-check nav-icon"></i> Đặt vé</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('schedule') }}"><i
                    class="la la-clock nav-icon"></i> Lịch trình</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('ticket') }}"><i
                    class="la la-ticket-alt nav-icon"></i> Vé</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('refund') }}"><i class="la la-undo nav-icon"></i>
                Trả vé</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('exchange') }}"><i
                    class="la la-sync-alt nav-icon"></i> Đổi vé</a></li>
    </ul>
</li>

<li class="nav-item menu-group">
    <a class="nav-link" href="#" onclick="toggleMenu('train-menu')"><i class="la la-train nav-icon"></i> Quản lý
        tàu</a>
    <ul class="nav nav-treeview" id="train-menu" style="display: none;">
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('train') }}"><i class="la la-train nav-icon"></i>
                Tàu</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('car') }}"><i class="la la-bus nav-icon"></i>
                Toa
                tàu</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('seat') }}"><i class="la la-chair nav-icon"></i>
                Ghế</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('seat-type') }}"><i
                    class="la la-cogs nav-icon"></i> Loại ghế</a></li>
    </ul>
</li>

<li class="nav-item menu-group">
    <a class="nav-link" href="#" onclick="toggleMenu('voucher-menu')"><i class="la la-gift nav-icon"></i> Quản lý
        khuyến mại</a>
    <ul class="nav nav-treeview" id="voucher-menu" style="display: none;">
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('voucher') }}"><i
                    class="la la-percent nav-icon"></i> Khuyến mại</a></li>
    </ul>
</li>

<li class="nav-item menu-group">
    <a class="nav-link" href="#" onclick="toggleMenu('policy-menu')"><i class="la la-clipboard-list nav-icon"></i>
        Quản lý chính sách</a>
    <ul class="nav nav-treeview" id="policy-menu" style="display: none;">
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('refund-policy') }}"><i
                    class="la la-exchange-alt nav-icon"></i> Chính sách trả vé</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('exchange-policy') }}"><i
                    class="la la-retweet nav-icon"></i> Chính sách đổi vé</a></li>
    </ul>
</li>

<script>
    // Function to toggle visibility of menu items
    function toggleMenu(menuId) {
        var menu = document.getElementById(menuId);
        if (menu.style.display === "none" || menu.style.display === "") {
            menu.style.display = "block"; // Show the menu
        } else {
            menu.style.display = "none"; // Hide the menu
        }
    }
</script>
