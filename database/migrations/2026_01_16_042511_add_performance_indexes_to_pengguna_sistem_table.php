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
        Schema::table('pengguna_sistem', function (Blueprint $table) {
            // Critical single-column indexes for performance
            $table->index('unit_id', 'idx_pengguna_unit_id');
            $table->index('aktif', 'idx_pengguna_aktif');
            $table->index('email_address', 'idx_pengguna_email');

            // Composite indexes for common query patterns
            $table->index(['unit_id', 'aktif'], 'idx_pengguna_unit_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengguna_sistem', function (Blueprint $table) {
            // Drop single-column indexes
            $table->dropIndex('idx_pengguna_unit_id');
            $table->dropIndex('idx_pengguna_aktif');
            $table->dropIndex('idx_pengguna_email');

            // Drop composite indexes
            $table->dropIndex('idx_pengguna_unit_active');
        });
    }
};
