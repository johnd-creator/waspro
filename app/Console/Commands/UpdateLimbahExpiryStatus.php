<?php

namespace App\Console\Commands;

use App\Models\LogPenyimpananLimbah;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateLimbahExpiryStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'limbah:update-expiry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update status limbah yang sudah kadaluarsa';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Mulai memperbarui status kadaluwarsa limbah...');

        $logs = LogPenyimpananLimbah::where('status_log', 'Tersimpan')
            ->where('maksimal_penyimpanan_tanggal', '<=', Carbon::now())
            ->get();

        foreach ($logs as $log) {
            $log->status_log = 'Kadaluarsa';
            $log->save();
        }

        $this->info('Pembaruan status kadaluwarsa limbah selesai. '.$logs->count().' log diperbarui.');
    }
}
