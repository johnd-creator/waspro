<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('audit_log', function (Blueprint $table) {
            $table->string('setting_category', 50)->after('table_name')->nullable();
            $table->string('setting_key')->after('setting_category')->nullable();
            $table->text('old_value_text')->after('old_value')->nullable();
            $table->text('new_value_text')->after('new_value')->nullable();
            // Indexes for faster queries
            $table->index('setting_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audit_log', function (Blueprint $table) {
            $table->dropColumn([
                'setting_category',
                'setting_key',
                'old_value_text',
                'new_value_text'
            ]);
        });
    }
};
