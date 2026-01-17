<?php

namespace Database\Seeders;

use App\Models\ApplicationSetting;
use App\Models\ApprovalLog;
use App\Models\AuditLog;
use App\Models\PeranPengguna;
use Illuminate\Database\Seeder;

class TestFactoriesSeeder extends Seeder
{
    public function run(): void
    {
        echo "Testing ApprovalLogFactory...\n";
        $approval = ApprovalLog::factory()->approved()->create();
        echo "✓ Created approved log: {$approval->action}\n";

        $rejected = ApprovalLog::factory()->rejected()->create();
        echo "✓ Created rejected log: {$rejected->action}\n";

        echo "\nTesting AuditLogFactory...\n";
        $audit = AuditLog::factory()->createAction()->create();
        echo "✓ Created audit log: {$audit->action}\n";

        $update = AuditLog::factory()->update()->create();
        echo "✓ Created update audit: {$update->action}\n";

        echo "\nTesting PeranPenggunaFactory...\n";
        $role = PeranPengguna::factory()->administrator()->create();
        echo "✓ Created role: {$role->nama_peran}\n";

        $operator = PeranPengguna::factory()->operator()->create();
        echo "✓ Created role: {$operator->nama_peran}\n";

        echo "\nTesting ApplicationSettingFactory...\n";
        $setting = ApplicationSetting::factory()->integer()->limbah()->create();
        echo "✓ Created setting: {$setting->key} ({$setting->type})\n";

        $boolSetting = ApplicationSetting::factory()->boolean()->system()->create();
        echo "✓ Created setting: {$boolSetting->key} ({$boolSetting->type})\n";

        echo "\n✅ All factories working correctly!\n";
    }
}
