<?php

use App\Services\DashboardService;
use Illuminate\Support\Facades\App;

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $service = App::make(DashboardService::class);
    $data = $service->getDashboardData([]);

    if (array_key_exists('isSuperAdmin', $data)) {
        echo "PASS: getDashboardData() contains key 'isSuperAdmin'.\n";
    } else {
        echo "FAIL: getDashboardData() missing key 'isSuperAdmin'. Keys found: " . implode(', ', array_keys($data)) . "\n";
        exit(1);
    }

} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
