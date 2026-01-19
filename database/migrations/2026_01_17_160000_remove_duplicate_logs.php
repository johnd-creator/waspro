<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Hapus duplikat log penyimpanan limbah berdasarkan timestamp dan kolom unik
     *
     * Menjaga satu log unik per kombinasi:
     * - tanggal_limbah_masuk (dibulat ke detik)
     * - unit_id
     * - kode_limbah
     * - perusahaan_id
     * - uraian_pekerjaan
     *
     * Untuk setiap kombinasi tersebut, hanya satu log yang akan dipertahankan
     * (log dengan ID paling baru untuk setiap kombinasi)
     */
    public function up(): void
    {
        // Hapus duplikat berdasarkan kombinasi kolom
        // Keep record dengan log_id tertinggi untuk setiap grup duplikat

        DB::statement('
            DELETE FROM log_penyimpanan_limbah
            WHERE log_id NOT IN (
                SELECT MAX(log_id)
                FROM log_penyimpanan_limbah
                GROUP BY
                    DATE(tanggal_limbah_masuk),
                    unit_id,
                    kode_limbah,
                    perusahaan_id,
                    uraian_pekerjaan
            )
        ');

        // Hapus duplikat berdasarkan kode_identitas (fallback untuk keaman)
        // Keep record dengan log_id tertinggi untuk setiap kode_identitas yang sama

        DB::statement('
            DELETE FROM log_penyimpanan_limbah
            WHERE log_id NOT IN (
                SELECT MAX(log_id)
                FROM log_penyimpanan_limbah
                WHERE kode_identitas IS NOT NULL
                GROUP BY kode_identitas
            )
        ');
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        // Tidak bisa reverse karena data yang sudah dihapus tidak dapat dipulihkan
        // Jalankan ulang seeder jika perlu data testing
    }
};
