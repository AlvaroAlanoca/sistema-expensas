<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule; // Asegúrate de importar el Schedule

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();


Schedule::command('expensas:verificar-vencimientos')->dailyAt('00:00');
Schedule::command('expensas:generar')->monthlyOn(1, '00:00');
Schedule::command('expensas:calcular-morosidad')->daily();