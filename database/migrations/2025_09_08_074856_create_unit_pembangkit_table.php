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
        Schema::create('unit_pembangkit', function (Blueprint $table) {
            $table->id('unit_id');
            $table->string('nama_unit');
            $table->text('alamat_unit')->nullable();
            $table->string('kota')->nullable();
            $table->string('kode_pos', 10)->nullable();
            $table->string('telepon_unit', 20)->nullable();
            $table->text('keterangan')->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_pembangkit');
    }
};
