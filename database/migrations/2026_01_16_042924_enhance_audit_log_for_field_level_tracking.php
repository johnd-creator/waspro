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
            // Add field-level tracking columns
            $table->string('field_name')->nullable()->after('table_name')->comment('Nama field yang diubah (untuk field-level tracking)');
            $table->text('old_value_simple')->nullable()->after('old_value')->comment('Nilai lama field (simple format)');
            $table->text('new_value_simple')->nullable()->after('new_value')->comment('Nilai baru field (simple format)');
            
            // Add business context columns
            $table->string('business_context')->nullable()->after('user_agent')->comment('Konteks bisnis transaksi');
            $table->text('reason')->nullable()->after('business_context')->comment('Alasan perubahan data');
            $table->string('session_id')->nullable()->after('reason')->comment('Session ID pengguna');
            
            // Add approval workflow columns
            $table->unsignedBigInteger('approved_by')->nullable()->after('session_id')->comment('User yang approve perubahan');
            $table->timestamp('approved_at')->nullable()->after('approved_by')->comment('Waktu approval');
            
            // Add indexes for field-level queries
            $table->index('field_name', 'idx_audit_field_name');
            $table->index('business_context', 'idx_audit_business_context');
            $table->index(['table_name', 'field_name'], 'idx_audit_table_field');
            $table->index(['user_id', 'table_name', 'field_name'], 'idx_audit_user_table_field');
            $table->index(['business_context', 'created_at'], 'idx_audit_context_created');
            
            // Add foreign key for approved_by
            $table->foreign('approved_by')->references('user_id')->on('pengguna_sistem')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audit_log', function (Blueprint $table) {
            // Drop indexes first
            $table->dropIndex('idx_audit_field_name');
            $table->dropIndex('idx_audit_business_context');
            $table->dropIndex('idx_audit_table_field');
            $table->dropIndex('idx_audit_user_table_field');
            $table->dropIndex('idx_audit_context_created');
            
            // Drop foreign key
            $table->dropForeign(['approved_by']);
            
            // Drop columns
            $table->dropColumn('field_name');
            $table->dropColumn('old_value_simple');
            $table->dropColumn('new_value_simple');
            $table->dropColumn('business_context');
            $table->dropColumn('reason');
            $table->dropColumn('session_id');
            $table->dropColumn('approved_by');
            $table->dropColumn('approved_at');
        });
    }
};
