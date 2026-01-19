<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('log_penyimpanan_limbah', function (Blueprint $table) {
            $table->index(['unit_id', 'expiry_status', 'tanggal_kadaluarsa'], 'idx_unit_expiry_date');
            $table->index(['unit_id', 'status_log', 'tanggal_limbah_masuk'], 'idx_unit_status_date');
            $table->index(['status_log', 'tanggal_pengangkutan'], 'idx_status_pengangkutan');
            $table->index('client_uuid', 'idx_client_uuid');
            $table->index(['kode_identitas', 'status_log'], 'idx_identitas_status');
        });

        Schema::table('pengguna_sistem', function (Blueprint $table) {
            $table->index(['unit_id', 'aktif', 'email_address'], 'idx_unit_active_email');
        });

        Schema::table('audit_log', function (Blueprint $table) {
            $table->index(['user_id', 'created_at', 'action'], 'idx_user_created_action');
            $table->index(['table_name', 'record_id', 'created_at'], 'idx_table_record_created');
        });

        Schema::table('log_penyimpanan_limbah', function (Blueprint $table) {
            $table->string('status_log_lower', 20)->nullable()->storedAs('LOWER(status_log)');
            $table->index('status_log_lower', 'idx_status_log_lower');
        });
    }

    public function down(): void
    {
        Schema::table('log_penyimpanan_limbah', function (Blueprint $table) {
            $table->dropIndex('idx_unit_expiry_date');
            $table->dropIndex('idx_unit_status_date');
            $table->dropIndex('idx_status_pengangkutan');
            $table->dropIndex('idx_client_uuid');
            $table->dropIndex('idx_identitas_status');
            $table->dropIndex('idx_status_log_lower');
            $table->dropColumn('status_log_lower');
        });

        Schema::table('pengguna_sistem', function (Blueprint $table) {
            $table->dropIndex('idx_unit_active_email');
        });

        Schema::table('audit_log', function (Blueprint $table) {
            $table->dropIndex('idx_user_created_action');
            $table->dropIndex('idx_table_record_created');
        });
    }
};
