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
        Schema::table('log_penyimpanan_limbah', function (Blueprint $table) {
            $table->date('tanggal_kadaluarsa')->nullable()->after('maksimal_penyimpanan_tanggal');
            $table->enum('expiry_status', ['Normal', 'Warning', 'Critical', 'Expired'])->default('Normal')->after('tanggal_kadaluarsa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('log_penyimpanan_limbah', function (Blueprint $table) {
            $table->dropColumn(['tanggal_kadaluarsa', 'expiry_status']);
        });
    }
};
