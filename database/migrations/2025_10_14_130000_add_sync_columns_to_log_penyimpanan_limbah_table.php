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
            $table->uuid('client_uuid')->nullable()->unique()->after('log_id');
            $table->dateTimeTz('created_at_client')->nullable()->after('updated_at');
            $table->dateTimeTz('updated_at_client')->nullable()->after('created_at_client');
            $table->dateTimeTz('synced_at')->nullable()->after('updated_at_client');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('log_penyimpanan_limbah', function (Blueprint $table) {
            $table->dropColumn(['client_uuid', 'created_at_client', 'updated_at_client', 'synced_at']);
        });
    }
};
