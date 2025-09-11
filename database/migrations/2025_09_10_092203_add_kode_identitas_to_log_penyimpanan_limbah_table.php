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
            $table->string('kode_identitas', 50)->unique()->nullable()->after('log_id');
            $table->index('kode_identitas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('log_penyimpanan_limbah', function (Blueprint $table) {
            $table->dropIndex(['kode_identitas']);
            $table->dropColumn('kode_identitas');
        });
    }
};
