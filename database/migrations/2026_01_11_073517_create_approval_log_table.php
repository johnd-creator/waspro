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
        Schema::create('approval_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('log_id')->constrained('log_penyimpanan_limbah', 'log_id')->cascadeOnDelete();
            $table->foreignId('approved_by')->constrained('pengguna_sistem', 'user_id');
            $table->enum('action', ['approve', 'reject']);
            $table->text('rejected_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_log');
    }
};
