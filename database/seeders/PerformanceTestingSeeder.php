<?php

namespace Database\Seeders;

use App\Models\JenisLimbah;
use App\Models\KarakteristikLimbah;
use App\Models\KategoriKegiatanSumber;
use App\Models\LogPenyimpananLimbah;
use App\Models\PenggunaSistem;
use App\Models\PeranPengguna;
use App\Models\PerusahaanPenghasil;
use App\Models\UnitPembangkit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PerformanceTestingSeeder extends Seeder
{
    private $startTime;

    private $totalSteps = 6;

    private $currentStep = 0;

    public function run(): void
    {
        $this->startTime = microtime(true);

        $this->command->info("\n╔════════════════════════════════════════════════════════════╗");
        $this->command->info('║   WASPRO Performance Testing Data Generator               ║');
        $this->command->info('║   Target: 50 units, 200 users, 50 jenis, 10,000 logs     ║');
        $this->command->info("╚════════════════════════════════════════════════════════════╝\n");

        // Disable query logging for performance
        DB::connection()->disableQueryLog();

        try {
            $this->step1_SupportingData();
            $this->step2_JenisLimbah();
            $this->step3_BulkUsers();
            $this->step4_BulkLogs();
            $this->step5_UpdateApprovalStatuses();
            $this->step6_FinalSummary();

            $this->showExecutionTime();

        } catch (\Exception $e) {
            $this->command->error("\n❌ Error: ".$e->getMessage());
            $this->command->error('File: '.$e->getFile().':'.$e->getLine());
            throw $e;
        }
    }

    private function step1_SupportingData(): void
    {
        $this->currentStep++;
        $this->command->info("📚 Step {$this->currentStep}/{$this->totalSteps}: Generating Supporting Data");
        $this->command->info('─────────────────────────────────────────────────────────────');

        DB::beginTransaction();

        try {
            // Ensure roles exist
            $this->ensureRoles();

            // Generate 50 Units
            $existingUnits = UnitPembangkit::count();
            if ($existingUnits < 50) {
                $needed = 50 - $existingUnits;
                $this->command->info("   Creating {$needed} units...");

                $cities = [
                    'Jakarta',
                    'Surabaya',
                    'Bandung',
                    'Medan',
                    'Semarang',
                    'Makassar',
                    'Palembang',
                    'Tangerang',
                    'Depok',
                    'Bekasi',
                    'Bogor',
                    'Malang',
                    'Yogyakarta',
                    'Balikpapan',
                    'Denpasar',
                    'Pontianak',
                    'Banjarmasin',
                    'Samarinda',
                    'Manado',
                    'Batam',
                ];

                for ($i = 0; $i < $needed; $i++) {
                    $city = $cities[$i % count($cities)];
                    $suffix = $i >= count($cities) ? ' '.(int) ($i / count($cities) + 1) : '';

                    UnitPembangkit::create([
                        'nama_unit' => "Unit Pembangkit {$city}{$suffix}",
                        'lokasi_unit' => "{$city}, Indonesia",
                        'kapasitas_mw' => rand(200, 600),
                    ]);

                    if (($i + 1) % 10 == 0) {
                        $this->showProgress($i + 1, $needed, 'units');
                    }
                }
                $this->command->info("   ✓ Created {$needed} units");
            } else {
                $this->command->info("   ✓ Units already exist: {$existingUnits}");
            }

            // Generate Karakteristik Limbah
            $existingKar = KarakteristikLimbah::count();
            if ($existingKar < 10) {
                $needed = 10 - $existingKar;
                KarakteristikLimbah::factory()->count($needed)->create();
                $this->command->info("   ✓ Created {$needed} karakteristik limbah");
            }

            // Generate Kategori Kegiatan
            $existingKat = KategoriKegiatanSumber::count();
            if ($existingKat < 20) {
                $needed = 20 - $existingKat;
                KategoriKegiatanSumber::factory()->count($needed)->create();
                $this->command->info("   ✓ Created {$needed} kategori kegiatan");
            }

            // Generate Perusahaan
            $existingPer = PerusahaanPenghasil::count();
            if ($existingPer < 50) {
                $needed = 50 - $existingPer;
                $this->command->info("   Creating {$needed} perusahaan...");

                for ($i = 0; $i < $needed; $i++) {
                    PerusahaanPenghasil::factory()->create();

                    if (($i + 1) % 10 == 0) {
                        $this->showProgress($i + 1, $needed, 'perusahaan');
                    }
                }
                $this->command->info("   ✓ Created {$needed} perusahaan");
            }

            DB::commit();
            $this->command->info('');

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function step2_JenisLimbah(): void
    {
        $this->currentStep++;
        $this->command->info("🗑️  Step {$this->currentStep}/{$this->totalSteps}: Generating Jenis Limbah (50 types)");
        $this->command->info('─────────────────────────────────────────────────────────────');

        DB::beginTransaction();

        try {
            $existingJenis = JenisLimbah::count();
            if ($existingJenis < 50) {
                $needed = 50 - $existingJenis;
                $this->command->info("   Creating {$needed} jenis limbah...");

                // 40 active, 10 inactive
                for ($i = 0; $i < $needed; $i++) {
                    $isActive = $i < ($needed - 10);

                    // Realistic Indonesian cost distribution
                    $cost = match (true) {
                        $i < 10 => fake()->randomFloat(2, 120000, 180000), // Very high
                        $i < 25 => fake()->randomFloat(2, 80000, 120000),  // High
                        $i < 40 => fake()->randomFloat(2, 50000, 80000),   // Medium
                        default => fake()->randomFloat(2, 30000, 50000),   // Low
                    };

                    JenisLimbah::factory()->create([
                        'biaya_pengangkutan_per_kg' => $cost,
                        'status_aktif' => $isActive,
                    ]);

                    if (($i + 1) % 10 == 0) {
                        $this->showProgress($i + 1, $needed, 'jenis limbah');
                    }
                }

                $this->command->info("   ✓ Created {$needed} jenis limbah (40 active, 10 inactive)");
            } else {
                $this->command->info("   ✓ Jenis limbah already exist: {$existingJenis}");
            }

            DB::commit();
            $this->command->info('');

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function step3_BulkUsers(): void
    {
        $this->currentStep++;
        $this->command->info("👥 Step {$this->currentStep}/{$this->totalSteps}: Generating Bulk Users (200 users)");
        $this->command->info('─────────────────────────────────────────────────────────────');

        DB::beginTransaction();

        try {
            $existingUsers = PenggunaSistem::count();
            $targetUsers = 200;

            if ($existingUsers >= $targetUsers) {
                $this->command->info("   ✓ Users already exist: {$existingUsers}");
                DB::commit();
                $this->command->info('');

                return;
            }

            $roles = [
                'Super Admin' => PeranPengguna::where('nama_peran', 'Super Admin')->first(),
                'Administrator' => PeranPengguna::where('nama_peran', 'Administrator')->first(),
                'Supervisor' => PeranPengguna::where('nama_peran', 'Supervisor')->first(),
                'Operator' => PeranPengguna::where('nama_peran', 'Operator')->first(),
            ];

            $units = UnitPembangkit::all();
            $created = 0;

            // 1 Super Admin
            if (! PenggunaSistem::whereHas('peranPengguna', fn ($q) => $q->where('nama_peran', 'Super Admin'))->exists()) {
                $superAdmin = PenggunaSistem::create([
                    'nama_lengkap' => 'Super Administrator Performance',
                    'email_address' => 'superadmin.perf@waspro.com',
                    'kata_sandi_hash' => Hash::make('password'),
                    'unit_id' => null,
                    'aktif' => true,
                    'email_verified_at' => now(),
                ]);
                $superAdmin->peranPengguna()->attach($roles['Super Admin']->peran_id);
                $created++;
            }

            $this->command->info('   Creating administrators (50)...');
            // 50 Administrators (1 per unit)
            foreach ($units as $index => $unit) {
                $admin = PenggunaSistem::create([
                    'nama_lengkap' => "Administrator {$unit->nama_unit}",
                    'email_address' => "admin.perf{$index}@waspro.com",
                    'kata_sandi_hash' => Hash::make('password'),
                    'unit_id' => $unit->unit_id,
                    'aktif' => true,
                    'email_verified_at' => now(),
                ]);
                $admin->peranPengguna()->attach($roles['Administrator']->peran_id);
                $created++;

                if (($index + 1) % 10 == 0) {
                    $this->showProgress($index + 1, 50, 'administrators');
                }
            }

            $this->command->info('   Creating supervisors (50)...');
            // 50 Supervisors (1 per unit)
            foreach ($units as $index => $unit) {
                $supervisor = PenggunaSistem::create([
                    'nama_lengkap' => "Supervisor {$unit->nama_unit}",
                    'email_address' => "supervisor.perf{$index}@waspro.com",
                    'kata_sandi_hash' => Hash::make('password'),
                    'unit_id' => $unit->unit_id,
                    'aktif' => true,
                    'email_verified_at' => now(),
                ]);
                $supervisor->peranPengguna()->attach($roles['Supervisor']->peran_id);
                $created++;

                if (($index + 1) % 10 == 0) {
                    $this->showProgress($index + 1, 50, 'supervisors');
                }
            }

            $this->command->info('   Creating operators (99)...');
            // 99 Operators (2 per unit for first 49 units, 1 for last unit)
            $operatorCount = 0;
            foreach ($units as $unitIndex => $unit) {
                $opsForUnit = $unitIndex < 49 ? 2 : 1;

                for ($i = 0; $i < $opsForUnit; $i++) {
                    $operator = PenggunaSistem::create([
                        'nama_lengkap' => 'Operator '.($operatorCount + 1)." {$unit->nama_unit}",
                        'email_address' => 'operator.perf'.($operatorCount + 1).'@waspro.com',
                        'kata_sandi_hash' => Hash::make('password'),
                        'unit_id' => $unit->unit_id,
                        'aktif' => true,
                        'email_verified_at' => now(),
                    ]);
                    $operator->peranPengguna()->attach($roles['Operator']->peran_id);
                    $created++;
                    $operatorCount++;

                    if ($operatorCount % 20 == 0) {
                        $this->showProgress($operatorCount, 99, 'operators');
                    }
                }
            }

            DB::commit();

            $this->command->info("   ✓ Created {$created} users total");
            $this->command->info('     - 1 Super Admin');
            $this->command->info('     - 50 Administrators');
            $this->command->info('     - 50 Supervisors');
            $this->command->info('     - 99 Operators');
            $this->command->info('');

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function step4_BulkLogs(): void
    {
        $this->currentStep++;
        $this->command->info("📦 Step {$this->currentStep}/{$this->totalSteps}: Generating Bulk Logs (10,000 logs)");
        $this->command->info('─────────────────────────────────────────────────────────────');
        $this->command->info('   This will take several minutes...');

        $targetLogs = 10000;
        $existingLogs = LogPenyimpananLimbah::withoutGlobalScopes()->count();

        if ($existingLogs >= $targetLogs) {
            $this->command->info("   ✓ Logs already exist: {$existingLogs}");
            $this->command->info('');

            return;
        }

        $toCreate = $targetLogs - $existingLogs;
        $batchSize = 500;
        $batches = ceil($toCreate / $batchSize);

        $this->command->info("   Creating {$toCreate} logs in {$batches} batches of {$batchSize}...");

        $created = 0;

        // Distribution targets
        $tersimpanTarget = (int) ($toCreate * 0.50);
        $diangkutTarget = (int) ($toCreate * 0.35);
        $expiredTarget = $toCreate - $tersimpanTarget - $diangkutTarget;

        for ($batch = 0; $batch < $batches; $batch++) {
            DB::beginTransaction();

            try {
                $logsInBatch = min($batchSize, $toCreate - $created);

                for ($i = 0; $i < $logsInBatch; $i++) {
                    $totalCreated = $created + $i;

                    // Determine status based on distribution
                    if ($totalCreated < $tersimpanTarget) {
                        LogPenyimpananLimbah::factory()->tersimpan()->create();
                    } elseif ($totalCreated < $tersimpanTarget + $diangkutTarget) {
                        LogPenyimpananLimbah::factory()->diangkut()->create();
                    } else {
                        LogPenyimpananLimbah::factory()->expired()->create();
                    }
                }

                DB::commit();
                $created += $logsInBatch;

                $this->showProgress($created, $toCreate, 'logs', true);

                // Clear memory
                if ($batch % 5 == 0) {
                    gc_collect_cycles();
                }

            } catch (\Exception $e) {
                DB::rollBack();
                $this->command->error("   Error in batch {$batch}: ".$e->getMessage());
                throw $e;
            }
        }

        $this->command->info("\n   ✓ Created {$created} logs");
        $this->command->info("     - ~{$tersimpanTarget} Tersimpan");
        $this->command->info("     - ~{$diangkutTarget} Diangkut");
        $this->command->info("     - ~{$expiredTarget} Expired");
        $this->command->info('');
    }

    private function step5_UpdateApprovalStatuses(): void
    {
        $this->currentStep++;
        $this->command->info("✅ Step {$this->currentStep}/{$this->totalSteps}: Updating Approval Statuses");
        $this->command->info('─────────────────────────────────────────────────────────────');

        $approvers = PenggunaSistem::whereHas('peranPengguna', function ($q) {
            $q->whereIn('nama_peran', ['Supervisor', 'Administrator', 'Super Admin']);
        })->where('aktif', true)->get();

        if ($approvers->isEmpty()) {
            $this->command->warn('   No approvers found, skipping approval status updates');

            return;
        }

        $this->command->info('   Updating approval statuses in batches...');

        $batchSize = 1000;
        $processed = 0;

        LogPenyimpananLimbah::withoutGlobalScopes()
            ->chunk($batchSize, function ($logs) use ($approvers, &$processed) {
                DB::beginTransaction();

                try {
                    foreach ($logs as $log) {
                        $rand = rand(1, 100);

                        if ($rand <= 50) {
                            // 50% pending
                            $log->update([
                                'approval_status' => 'pending',
                                'approved_by' => null,
                                'approved_at' => null,
                                'rejected_reason' => null,
                            ]);
                        } elseif ($rand <= 80) {
                            // 30% approved
                            $approver = $approvers->random();
                            $log->update([
                                'approval_status' => 'approved',
                                'approved_by' => $approver->user_id,
                                'approved_at' => now()->subDays(rand(1, 90)),
                                'rejected_reason' => null,
                            ]);
                        } else {
                            // 20% rejected
                            $approver = $approvers->random();
                            $reasons = [
                                'Data tidak lengkap',
                                'Dokumen tidak sesuai',
                                'Informasi limbah tidak valid',
                                'Perlu verifikasi ulang',
                            ];
                            $log->update([
                                'approval_status' => 'rejected',
                                'approved_by' => $approver->user_id,
                                'approved_at' => now()->subDays(rand(1, 90)),
                                'rejected_reason' => $reasons[array_rand($reasons)],
                            ]);
                        }

                        $processed++;
                    }

                    DB::commit();
                    $this->showProgress($processed, LogPenyimpananLimbah::withoutGlobalScopes()->count(), 'logs updated', true);

                } catch (\Exception $e) {
                    DB::rollBack();
                    throw $e;
                }
            });

        $this->command->info("\n   ✓ Updated {$processed} logs with approval statuses");
        $this->command->info('');
    }

    private function step6_FinalSummary(): void
    {
        $this->currentStep++;
        $this->command->info("📊 Step {$this->currentStep}/{$this->totalSteps}: Final Summary");
        $this->command->info('─────────────────────────────────────────────────────────────');

        $units = UnitPembangkit::count();
        $users = PenggunaSistem::count();
        $jenis = JenisLimbah::count();
        $logs = LogPenyimpananLimbah::withoutGlobalScopes()->count();
        $karakteristik = KarakteristikLimbah::count();
        $kategori = KategoriKegiatanSumber::count();
        $perusahaan = PerusahaanPenghasil::count();

        $this->command->info('   Total Data Generated:');
        $this->command->info("   ├─ Units: {$units}");
        $this->command->info("   ├─ Users: {$users}");
        $this->command->info("   ├─ Jenis Limbah: {$jenis}");
        $this->command->info('   ├─ Logs: '.number_format($logs));
        $this->command->info("   ├─ Karakteristik: {$karakteristik}");
        $this->command->info("   ├─ Kategori: {$kategori}");
        $this->command->info("   └─ Perusahaan: {$perusahaan}");

        $pending = LogPenyimpananLimbah::withoutGlobalScopes()->where('approval_status', 'pending')->count();
        $approved = LogPenyimpananLimbah::withoutGlobalScopes()->where('approval_status', 'approved')->count();
        $rejected = LogPenyimpananLimbah::withoutGlobalScopes()->where('approval_status', 'rejected')->count();

        $this->command->info("\n   Approval Status Distribution:");
        $this->command->info('   ├─ Pending: '.number_format($pending).' ('.round($pending / $logs * 100, 1).'%)');
        $this->command->info('   ├─ Approved: '.number_format($approved).' ('.round($approved / $logs * 100, 1).'%)');
        $this->command->info('   └─ Rejected: '.number_format($rejected).' ('.round($rejected / $logs * 100, 1).'%)');

        $critical = LogPenyimpananLimbah::withoutGlobalScopes()->where('expiry_status', 'Critical')->count();
        $warning = LogPenyimpananLimbah::withoutGlobalScopes()->where('expiry_status', 'Warning')->count();
        $safe = LogPenyimpananLimbah::withoutGlobalScopes()->where('expiry_status', 'Safe')->count();
        $expired = LogPenyimpananLimbah::withoutGlobalScopes()->where('expiry_status', 'Expired')->count();

        $this->command->info("\n   Expiry Status Distribution:");
        $this->command->info('   ├─ Critical: '.number_format($critical));
        $this->command->info('   ├─ Warning: '.number_format($warning));
        $this->command->info('   ├─ Safe: '.number_format($safe));
        $this->command->info('   └─ Expired: '.number_format($expired));

        $memoryUsage = memory_get_peak_usage(true) / 1024 / 1024;
        $this->command->info("\n   Performance Metrics:");
        $this->command->info('   └─ Peak Memory Usage: '.round($memoryUsage, 2).' MB');
    }

    private function ensureRoles(): void
    {
        $roles = [
            ['nama_peran' => 'Super Admin', 'deskripsi' => 'Akses penuh ke seluruh sistem', 'is_active' => true],
            ['nama_peran' => 'Administrator', 'deskripsi' => 'Dapat mengelola semua data dalam unit', 'is_active' => true],
            ['nama_peran' => 'Supervisor', 'deskripsi' => 'Dapat menyetujui dan memverifikasi data', 'is_active' => true],
            ['nama_peran' => 'Operator', 'deskripsi' => 'Dapat mengelola data limbah', 'is_active' => true],
        ];

        foreach ($roles as $role) {
            PeranPengguna::firstOrCreate(['nama_peran' => $role['nama_peran']], $role);
        }
    }

    private function showProgress(int $current, int $total, string $label, bool $sameLine = false): void
    {
        $percentage = round(($current / $total) * 100, 1);
        $bar = str_repeat('█', (int) ($percentage / 2));
        $space = str_repeat('░', 50 - (int) ($percentage / 2));

        $message = "   [{$bar}{$space}] {$percentage}% ({$current}/{$total} {$label})";

        if ($sameLine) {
            $this->command->getOutput()->write("\r".$message);
        } else {
            $this->command->info($message);
        }
    }

    private function showExecutionTime(): void
    {
        $endTime = microtime(true);
        $executionTime = $endTime - $this->startTime;

        $minutes = floor($executionTime / 60);
        $seconds = $executionTime % 60;

        $this->command->info("\n╔════════════════════════════════════════════════════════════╗");
        $this->command->info('║   ✅ Performance Testing Data Generation Complete!        ║');
        $this->command->info("║   Execution Time: {$minutes}m ".round($seconds, 2).'s'.str_repeat(' ', 40 - strlen("{$minutes}m ".round($seconds, 2).'s')).'║');
        $this->command->info("╚════════════════════════════════════════════════════════════╝\n");
    }
}
