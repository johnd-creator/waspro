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
        Schema::create('log_penyimpanan_limbah', function (Blueprint $table) {
            $table->id('log_id');
            $table->timestamp('timestamp_input')->useCurrent();
            $table->date('tanggal_limbah_masuk');
            $table->text('detail_sumber_limbah');
            $table->decimal('jumlah_limbah_masuk', 10, 2);
            $table->date('maksimal_penyimpanan_tanggal');
            $table->string('status_log');
            $table->date('tanggal_pengangkutan')->nullable();
            $table->decimal('jumlah_diangkut', 10, 2)->default(0);
            $table->unsignedBigInteger('user_id');
            $table->string('kode_limbah');
            $table->unsignedBigInteger('perusahaan_id')->nullable();
            $table->unsignedBigInteger('unit_id');
            $table->foreign('user_id')->references('user_id')->on('pengguna_sistem');
            $table->foreign('kode_limbah')->references('kode_limbah')->on('jenis_limbah');
            $table->foreign('perusahaan_id')->references('perusahaan_id')->on('perusahaan_penghasil');
            $table->foreign('unit_id')->references('unit_id')->on('unit_pembangkit');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_penyimpanan_limbah');
    }
};
