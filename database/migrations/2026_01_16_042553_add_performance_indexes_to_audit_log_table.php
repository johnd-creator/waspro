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
        Schema::table('audit_log', function (Blueprint $table) {
            // Critical single-column indexes for performance
            $table->index('user_id', 'idx_audit_user_id');
            $table->index('table_name', 'idx_audit_table_name');
            $table->index('record_id', 'idx_audit_record_id');
            $table->index('action', 'idx_audit_action');
            $table->index('created_at', 'idx_audit_created_at');

            // Composite indexes for common query patterns
            $table->index(['user_id', 'table_name'], 'idx_audit_user_table');
            $table->index(['table_name', 'record_id'], 'idx_audit_table_record');
            $table->index(['user_id', 'created_at'], 'idx_audit_user_created');
            $table->index(['table_name', 'action', 'created_at'], 'idx_audit_table_action_created');
            $table->index(['created_at', 'user_id'], 'idx_audit_created_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audit_log', function (Blueprint $table) {
            // Drop single-column indexes
            $table->dropIndex('idx_audit_user_id');
            $table->dropIndex('idx_audit_table_name');
            $table->dropIndex('idx_audit_record_id');
            $table->dropIndex('idx_audit_action');
            $table->dropIndex('idx_audit_created_at');

            // Drop composite indexes
            $table->dropIndex('idx_audit_user_table');
            $table->dropIndex('idx_audit_table_record');
            $table->dropIndex('idx_audit_user_created');
            $table->dropIndex('idx_audit_table_action_created');
            $table->dropIndex('idx_audit_created_user');
        });
    }
};
