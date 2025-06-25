<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('logs:clear', function () {
    array_map('unlink', array_filter((array) glob(storage_path('logs/*.log'))));
    $this->info('Logs has been cleared!');
})->describe('Clear log files');
