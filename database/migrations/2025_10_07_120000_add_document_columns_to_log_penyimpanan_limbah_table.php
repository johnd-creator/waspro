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
        Schema::table('log_penyimpanan_limbah', function (Blueprint $table): void {
            $table->string('dokumen_path')->nullable()->after('expiry_status');
            $table->string('dokumen_original_name')->nullable()->after('dokumen_path');
            $table->string('dokumen_mime')->nullable()->after('dokumen_original_name');
            $table->unsignedBigInteger('dokumen_size')->nullable()->after('dokumen_mime');
            $table->timestamp('dokumen_uploaded_at')->nullable()->after('dokumen_size');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('log_penyimpanan_limbah', function (Blueprint $table): void {
            $table->dropColumn([
                'dokumen_path',
                'dokumen_original_name',
                'dokumen_mime',
                'dokumen_size',
                'dokumen_uploaded_at',
            ]);
        });
    }
};
