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
            $table->renameColumn('status_aktif', 'status_aktif');
        });

        Schema::table('karakteristik_limbah', function (Blueprint $table) {
            $table->renameColumn('status_aktif', 'status_aktif');
        });

        Schema::table('perusahaan_penghasil', function (Blueprint $table) {
            $table->renameColumn('status_aktif', 'status_aktif');
        });

        Schema::table('unit_pembangkit', function (Blueprint $table) {
            $table->renameColumn('status_aktif', 'status_aktif');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jenis_limbah', function (Blueprint $table) {
            $table->renameColumn('status_aktif', 'status_aktif');
        });

        Schema::table('karakteristik_limbah', function (Blueprint $table) {
            $table->renameColumn('status_aktif', 'status_aktif');
        });

        Schema::table('perusahaan_penghasil', function (Blueprint $table) {
            $table->renameColumn('status_aktif', 'status_aktif');
        });

        Schema::table('unit_pembangkit', function (Blueprint $table) {
            $table->renameColumn('status_aktif', 'status_aktif');
        });
    }
};
