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
        Schema::table('log_penyimpanan_limbah', function (Blueprint $table) {
            $table->enum('expiry_status', ['Safe', 'Warning', 'Critical', 'Expired'])
                ->default('Safe')
                ->change();
        });

        DB::table('log_penyimpanan_limbah')
            ->where('expiry_status', 'Normal')
            ->update(['expiry_status' => 'Safe']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('log_penyimpanan_limbah', function (Blueprint $table) {
            $table->enum('expiry_status', ['Normal', 'Warning', 'Critical', 'Expired'])
                ->default('Normal')
                ->change();
        });

        DB::table('log_penyimpanan_limbah')
            ->where('expiry_status', 'Safe')
            ->update(['expiry_status' => 'Normal']);
    }
};
