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
        Schema::create('jenis_limbah', function (Blueprint $table) {
            $table->string('kode_limbah')->primary();
            $table->string('nama_limbah');
            $table->string('kemasan');
            $table->decimal('jumlah_ton_per_tahun', 10, 2);
            $table->integer('waktu_penyimpanan_hari');
            $table->unsignedBigInteger('karakteristik_id');
            $table->unsignedBigInteger('kategori_id');
            $table->foreign('karakteristik_id')->references('karakteristik_id')->on('karakteristik_limbah');
            $table->foreign('kategori_id')->references('kategori_id')->on('kategori_kegiatan_sumber');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_limbah');
    }
};
