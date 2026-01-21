<?php

use App\Services\DashboardService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $service = App::make(DashboardService::class);
    $units = $service->getUnits();

    if ($units instanceof Collection) {
        echo "PASS: getUnits() returns instance of Collection.\n";
        echo "Count: " . $units->count() . "\n";
        $first = $units->first();
        if ($first) {
            echo "First Item Type: " . get_class($first) . "\n";
            echo "ID: " . $first->unit_id . "\n";
        }
    } else {
        echo "FAIL: getUnits() returned " . gettype($units) . "\n";
        exit(1);
    }

} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
    exit(1);
}
