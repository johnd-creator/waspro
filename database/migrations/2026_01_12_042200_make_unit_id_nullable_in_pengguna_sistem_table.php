<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Drop existing foreign key constraint
        Schema::table('pengguna_sistem', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
        });

        // Step 2: Make unit_id nullable
        Schema::table('pengguna_sistem', function (Blueprint $table) {
            $table->unsignedBigInteger('unit_id')->nullable()->change();
        });

        // Step 3: Re-add foreign key (now nullable)
        Schema::table('pengguna_sistem', function (Blueprint $table) {
            $table->foreign('unit_id')
                ->nullable()
                ->references('unit_id')
                ->on('unit_pembangkit')
                ->onDelete('set null');
        });

        // Step 4: Migrate existing Super Admin to have unit_id = NULL
        $superAdminUsers = DB::table('pengguna_sistem as ps')
            ->join('pengguna_peran as pr', 'ps.user_id', '=', 'pr.user_id')
            ->join('peran_pengguna as pp', 'pr.peran_id', '=', 'pp.peran_id')
            ->where('pp.nama_peran', 'Super Admin')
            ->pluck('ps.user_id');

        DB::table('pengguna_sistem')
            ->whereIn('user_id', $superAdminUsers)
            ->update(['unit_id' => null]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $nullUnitUsers = DB::table('pengguna_sistem')
            ->whereNull('unit_id')
            ->pluck('user_id');

        if ($nullUnitUsers->isNotEmpty()) {
            $defaultUnit = DB::table('unit_pembangkit')->first();
            DB::table('pengguna_sistem')
                ->whereIn('user_id', $nullUnitUsers)
                ->update(['unit_id' => $defaultUnit->unit_id]);
        }

        // Drop foreign key
        Schema::table('pengguna_sistem', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
        });

        // Make unit_id NOT NULL again
        Schema::table('pengguna_sistem', function (Blueprint $table) {
            $table->unsignedBigInteger('unit_id')->nullable(false)->change();
        });

        // Re-add foreign key (non-nullable)
        Schema::table('pengguna_sistem', function (Blueprint $table) {
            $table->foreign('unit_id')
                ->references('unit_id')
                ->on('unit_pembangkit');
        });
    }
};
