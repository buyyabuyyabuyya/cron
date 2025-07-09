<?php

use Illuminate\Support\Facades\Schedule;

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ───── Monthly quota reset for paid users ───────────────────────────────
Schedule::command('users:reset-monthly-messages')
    ->monthlyOn(1, '00:05')   // first day of month at 00:05 UTC
    ->withoutOverlapping()
    ->onOneServer();
