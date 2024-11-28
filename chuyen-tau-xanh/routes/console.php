<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::call(function () {
    app(\App\Http\Controllers\ExchangeController::class)->updateExchangeStatus();
})->daily();

Schedule::call(function () {
    app(\App\Http\Controllers\RefundController::class)->updateRefundStatus();
})->daily();
