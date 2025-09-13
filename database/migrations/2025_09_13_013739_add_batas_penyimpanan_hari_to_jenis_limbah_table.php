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
        Schema::table('jenis_limbah', function (Blueprint $table) {
            $table->integer('batas_penyimpanan_hari')->nullable()->after('kemasan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jenis_limbah', function (Blueprint $table) {
            $table->dropColumn('batas_penyimpanan_hari');
        });
    }
};
