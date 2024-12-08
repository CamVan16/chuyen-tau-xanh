{{-- This file is used for menu items by any Backpack v6 theme --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

<x-backpack::menu-item title="Khách hàng" icon="la la-users" :link="backpack_url('customer')" />
<x-backpack::menu-item title="Đặt vé" icon="la la-calendar-check" :link="backpack_url('booking')" />
<x-backpack::menu-item title="Vé" icon="la la-ticket-alt" :link="backpack_url('ticket')" />
<x-backpack::menu-item title="Lịch trình" icon="la la-clock" :link="backpack_url('schedule')" />
<x-backpack::menu-item title="Chính sách trả vé" icon="la la-exchange-alt" :link="backpack_url('refund-policy')" />
<x-backpack::menu-item title="Trả vé" icon="la la-undo" :link="backpack_url('refund')" />
<x-backpack::menu-item title="Chính sách đổi vé" icon="la la-retweet" :link="backpack_url('exchange-policy')" />
<x-backpack::menu-item title="Đổi vé" icon="la la-sync-alt" :link="backpack_url('exchange')" />
