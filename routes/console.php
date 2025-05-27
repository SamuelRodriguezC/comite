<?php

use Carbon\Carbon;
use App\Enums\State;
use App\Models\Process;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Scheduling\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');



Artisan::command('processes:update-expired', function () {
    $now = Carbon::now();

    $expiredProcesses = Process::where('delivery_date', '<', $now)
        ->where('state', '!=', State::VENCIDO->value)
        ->get();

    foreach ($expiredProcesses as $process) {
        $process->state = State::VENCIDO->value;
        $process->save();
    }

    $this->info("Updated {$expiredProcesses->count()} processes to VENCIDO.");
})->describe('Update the state of processes to VENCIDO if delivery_date is past due');

// Programar la tarea periódica
app()->booted(function () {
    $schedule = app(Schedule::class);
    $schedule->command('processes:update-expired')->everySecond();
});
