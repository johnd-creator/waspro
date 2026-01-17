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
            // Critical single-column indexes for performance
            $table->index('unit_id', 'idx_log_unit_id');
            $table->index('status_log', 'idx_log_status');
            $table->index('tanggal_limbah_masuk', 'idx_log_tanggal');
            $table->index('expiry_status', 'idx_log_expiry_status');
            $table->index('approval_status', 'idx_log_approval_status');
            $table->index('user_id', 'idx_log_user_id');
            $table->index('kode_limbah', 'idx_log_kode_limbah');
            $table->index('perusahaan_id', 'idx_log_perusahaan_id');

            // Composite indexes for common query patterns
            $table->index(['unit_id', 'status_log'], 'idx_log_unit_status');
            $table->index(['unit_id', 'tanggal_limbah_masuk'], 'idx_log_unit_tanggal');
            $table->index(['unit_id', 'expiry_status'], 'idx_log_unit_expiry');
            $table->index(['user_id', 'unit_id'], 'idx_log_user_unit');
            $table->index(['status_log', 'tanggal_limbah_masuk'], 'idx_log_status_tanggal');
            $table->index(['expiry_status', 'tanggal_kadaluarsa'], 'idx_log_expiry_kadaluarsa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('log_penyimpanan_limbah', function (Blueprint $table) {
            // Drop single-column indexes
            $table->dropIndex('idx_log_unit_id');
            $table->dropIndex('idx_log_status');
            $table->dropIndex('idx_log_tanggal');
            $table->dropIndex('idx_log_expiry_status');
            $table->dropIndex('idx_log_approval_status');
            $table->dropIndex('idx_log_user_id');
            $table->dropIndex('idx_log_kode_limbah');
            $table->dropIndex('idx_log_perusahaan_id');

            // Drop composite indexes
            $table->dropIndex('idx_log_unit_status');
            $table->dropIndex('idx_log_unit_tanggal');
            $table->dropIndex('idx_log_unit_expiry');
            $table->dropIndex('idx_log_user_unit');
            $table->dropIndex('idx_log_status_tanggal');
            $table->dropIndex('idx_log_expiry_kadaluarsa');
        });
    }
};
