<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('jenis_limbah', function (Blueprint $table) {
            $table->decimal('biaya_pengangkutan_per_kg', 10, 2)->comment('Biaya pengangkutan per kilogram (Rupiah)');
            $table->date('mulai_berlaku')->comment('Tanggal mulai berlaku biaya pengangkutan');
            $table->date('akhir_berlaku')->nullable()->comment('Tanggal akhir berlaku biaya pengangkutan');
            $table->text('keterangan_biaya')->nullable()->comment('Keterangan tambahan mengenai biaya pengangkutan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jenis_limbah', function (Blueprint $table) {
            $table->dropColumn('biaya_pengangkutan_per_kg');
            $table->dropColumn('mulai_berlaku');
            $table->dropColumn('akhir_berlaku');
            $table->dropColumn('keterangan_biaya');
        });
    }
};
