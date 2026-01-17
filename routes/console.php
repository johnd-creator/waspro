<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\ApplicationSetting;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule waste expiry status update with dynamic frequency
Schedule::command('waste:update-expiry-status')
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/waste-expiry-scheduler.log'))
    ->everyMinute()
    ->when(function () {
        try {
            // Get interval from settings (default 60 minutes)
            $interval = (int) ApplicationSetting::getValue('notification.expiry_check_interval_minutes', 60);

            $lastRunKey = 'expiry_check_last_run';
            $lastRun = Cache::get($lastRunKey);

            return !$lastRun || now()->diffInMinutes($lastRun) >= $interval;
        } catch (\Throwable $e) {
            Log::error('Expiry scheduler check failed: ' . $e->getMessage());
            return false;
        }
    });

// Schedule cleanup of stale pending logs (hourly)
Schedule::command('waste:cleanup-pending-logs')->hourly();

// Schedule monthly report generation (runs daily to check if date matches)
Schedule::command('report:generate-monthly')->dailyAt('02:00');
