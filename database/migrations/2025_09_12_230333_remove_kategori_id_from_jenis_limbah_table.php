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
            $table->dropForeign(['kategori_id']);
            $table->dropColumn('kategori_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jenis_limbah', function (Blueprint $table) {
            $table->unsignedBigInteger('kategori_id')->nullable()->after('karakteristik_id');
            $table->foreign('kategori_id')->references('kategori_id')->on('kategori_kegiatan_sumber')->onDelete('set null');
        });
    }
};
