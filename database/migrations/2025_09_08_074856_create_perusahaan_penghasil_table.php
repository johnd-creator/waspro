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
        Schema::create('perusahaan_penghasil', function (Blueprint $table) {
            $table->id('perusahaan_id');
            $table->string('nama_perusahaan')->unique();
            $table->string('jenis_perusahaan')->nullable();
            $table->string('npwp', 20)->nullable();
            $table->string('telepon', 15)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('kota', 50)->nullable();
            $table->text('alamat_perusahaan');
            $table->string('person_in_charge', 100)->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perusahaan_penghasil');
    }
};
