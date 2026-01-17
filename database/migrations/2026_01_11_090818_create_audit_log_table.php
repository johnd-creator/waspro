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
        Schema::create('audit_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('pengguna_sistem', 'user_id')->onDelete('cascade');
            $table->string('action')->comment('create, update, delete');
            $table->string('table_name')->comment('nama tabel yang diubah');
            $table->unsignedBigInteger('record_id')->comment('ID record yang diubah');
            $table->text('old_value')->nullable()->comment('data sebelum diubah dalam format JSON');
            $table->text('new_value')->nullable()->comment('data sesudah diubah dalam format JSON');
            $table->string('ip_address')->nullable()->comment('IP address pengguna');
            $table->string('user_agent')->nullable()->comment('User agent browser pengguna');
            $table->timestamps();

            $table->index(['user_id', 'action']);
            $table->index(['table_name', 'record_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_log');
    }
};
