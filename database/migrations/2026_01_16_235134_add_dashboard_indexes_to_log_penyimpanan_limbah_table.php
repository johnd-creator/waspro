<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * Dashboard query optimization indexes for log_penyimpanan_limbah table.
     * These indexes improve performance of:
     * - Date range queries (tanggal_limbah_masuk, created_at)
     * - Status filtering (status_log)
     * - Unit scoping (unit_id)
     * - Foreign key lookups (kode_limbah, perusahaan_id)
     * - Near expiry queries (maksimal_penyimpanan_tanggal)
     */
    public function up(): void
    {
        Schema::table('log_penyimpanan_limbah', function (Blueprint $table) {
            // Date columns for time-based filtering and aggregations
            $table->index('tanggal_limbah_masuk', 'idx_lpl_tanggal_limbah_masuk');
            $table->index('created_at', 'idx_lpl_created_at');
            $table->index('maksimal_penyimpanan_tanggal', 'idx_lpl_maksimal_penyimpanan');

            // Status for filtering (Tersimpan, Diangkut, Kadaluarsa)
            $table->index('status_log', 'idx_lpl_status_log');

            // Unit ID for scoping and filtering
            $table->index('unit_id', 'idx_lpl_unit_id');

            // Foreign keys for JOIN operations
            $table->index('kode_limbah', 'idx_lpl_kode_limbah');
            $table->index('perusahaan_id', 'idx_lpl_perusahaan_id');

            // Composite index for common query patterns
            // Pattern: WHERE status_log = 'Tersimpan' AND unit_id = X AND tanggal >= Y
            $table->index(['status_log', 'unit_id', 'tanggal_limbah_masuk'], 'idx_lpl_status_unit_tanggal');

            // Pattern: WHERE created_at >= X ORDER BY created_at DESC (recent activities)
            $table->index('created_at', 'idx_lpl_recent_activities');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('log_penyimpanan_limbah', function (Blueprint $table) {
            // Drop all indexes in reverse order
            $table->dropIndex('idx_lpl_recent_activities');
            $table->dropIndex('idx_lpl_status_unit_tanggal');
            $table->dropIndex('idx_lpl_perusahaan_id');
            $table->dropIndex('idx_lpl_kode_limbah');
            $table->dropIndex('idx_lpl_unit_id');
            $table->dropIndex('idx_lpl_status_log');
            $table->dropIndex('idx_lpl_maksimal_penyimpanan');
            $table->dropIndex('idx_lpl_created_at');
            $table->dropIndex('idx_lpl_tanggal_limbah_masuk');
        });
    }
};
