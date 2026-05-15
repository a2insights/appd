<?php

use App\Jobs\MarcarCarteirinhasVencidas;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use ShuvroRoy\FilamentSpatieLaravelBackup\Jobs\CreateBackupJob;
use ShuvroRoy\FilamentSpatieLaravelBackup\Enums\Option;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::job(new MarcarCarteirinhasVencidas)->daily();

Schedule::job(new CreateBackupJob(Option::ONLY_DB))->dailyAt('00:00');
