<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('jenis_limbah', function (Blueprint $table) {
            // Fix: Make jumlah_ton_per_tahun nullable
            $table->decimal('jumlah_ton_per_tahun', 10, 2)->nullable()->change();

            // Fix: Make deskripsi_limbah nullable
            $table->text('deskripsi_limbah')->nullable()->change();

            // Fix: Make all biaya columns nullable
            $table->decimal('biaya_pengangkutan_per_kg', 10, 2)->nullable()->change();
            $table->date('mulai_berlaku')->nullable()->change();
            $table->date('akhir_berlaku')->nullable()->change();
            $table->text('keterangan_biaya')->nullable()->change();

            // Fix: Make batas_penyimpanan_hari nullable
            $table->integer('batas_penyimpanan_hari')->nullable()->change();

            // Set default values for existing NULL records
            DB::table('jenis_limbah')
                ->whereNull('status_aktif')
                ->update(['status_aktif' => true]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jenis_limbah', function (Blueprint $table) {
            $table->decimal('jumlah_ton_per_tahun', 10, 2)->change();
            $table->text('deskripsi_limbah')->change();
            $table->decimal('biaya_pengangkutan_per_kg', 10, 2)->change();
            $table->date('mulai_berlaku')->nullable()->change();
            $table->date('akhir_berlaku')->nullable()->change();
            $table->text('keterangan_biaya')->nullable()->change();
            $table->integer('batas_penyimpanan_hari')->change();
        });
    }
};
