<?php

namespace Tests\Feature\Api;

use App\Models\PenggunaSistem;
use App\Models\PeranPengguna;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

abstract class ApiTestCase extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->refreshTestingSchema();
        $this->seedDefaultRoles();
    }

    protected function refreshTestingSchema(): void
    {
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('personal_access_tokens');
        Schema::dropIfExists('pengguna_peran');
        Schema::dropIfExists('peran_pengguna');
        Schema::dropIfExists('perusahaan_penghasil');
        Schema::dropIfExists('jenis_limbah');
        Schema::dropIfExists('kategori_kegiatan_sumber');
        Schema::dropIfExists('karakteristik_limbah');
        Schema::dropIfExists('pengguna_sistem');
        Schema::dropIfExists('unit_pembangkit');

        Schema::create('unit_pembangkit', function (Blueprint $table): void {
            $table->increments('unit_id');
            $table->string('nama_unit');
            $table->text('alamat_unit')->nullable();
            $table->string('kota', 100)->nullable();
            $table->string('kode_pos', 10)->nullable();
            $table->string('telepon_unit', 20)->nullable();
            $table->text('keterangan')->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
        });

        Schema::create('pengguna_sistem', function (Blueprint $table): void {
            $table->increments('user_id');
            $table->string('email_address')->unique();
            $table->string('nama_lengkap');
            $table->string('kata_sandi_hash');
            $table->tinyInteger('aktif')->default(1);
            $table->unsignedInteger('unit_id');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->timestamps();
        });

        Schema::create('peran_pengguna', function (Blueprint $table): void {
            $table->increments('peran_id');
            $table->string('nama_peran')->unique();
            $table->text('deskripsi')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('pengguna_peran', function (Blueprint $table): void {
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('peran_id');
            $table->primary(['user_id', 'peran_id']);
            $table->timestamps();
        });

        Schema::create('karakteristik_limbah', function (Blueprint $table): void {
            $table->increments('karakteristik_id');
            $table->string('nama_karakteristik');
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
        });

        Schema::create('kategori_kegiatan_sumber', function (Blueprint $table): void {
            $table->increments('kategori_id');
            $table->string('nama_kategori');
            $table->timestamps();
        });

        Schema::create('jenis_limbah', function (Blueprint $table): void {
            $table->string('kode_limbah')->primary();
            $table->string('nama_limbah');
            $table->string('kemasan');
            $table->decimal('jumlah_ton_per_tahun', 10, 2)->default(0);
            $table->integer('waktu_penyimpanan_hari')->default(0);
            $table->integer('batas_penyimpanan_hari')->nullable();
            $table->unsignedInteger('karakteristik_id')->nullable();
            $table->unsignedInteger('kategori_id')->nullable(); // Added missing column
            $table->text('deskripsi_limbah')->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
        });

        Schema::create('perusahaan_penghasil', function (Blueprint $table): void {
            $table->increments('perusahaan_id');
            $table->string('nama_perusahaan')->unique();
            $table->string('jenis_perusahaan')->nullable();
            $table->string('npwp', 20)->nullable();
            $table->string('telepon', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('kota', 100)->nullable();
            $table->text('alamat_perusahaan');
            $table->string('person_in_charge', 100)->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::create('personal_access_tokens', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('tokenable_type');
            $table->unsignedInteger('tokenable_id');
            $table->string('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });

        Schema::create('notifications', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->string('notifiable_type');
            $table->unsignedInteger('notifiable_id');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    protected function seedDefaultRoles(): void
    {
        $roles = [
            'Super Admin' => 'Memiliki akses penuh terhadap sistem.',
            'Administrator' => 'Mengelola master data dan operasional unit.',
            'Operator' => 'Mengelola data operasional harian.',
            'Viewer' => 'Hanya dapat melihat data.',
        ];

        foreach ($roles as $name => $description) {
            PeranPengguna::create([
                'nama_peran' => $name,
                'deskripsi' => $description,
                'is_active' => true,
            ]);
        }
    }

    protected function createUserWithRoles(array|string $roles, array $attributes = []): PenggunaSistem
    {
        $roles = is_array($roles) ? $roles : [$roles];

        $user = PenggunaSistem::factory()->create($attributes);

        $roleIds = PeranPengguna::whereIn('nama_peran', $roles)->pluck('peran_id');

        foreach ($roleIds as $roleId) {
            $user->peranPengguna()->attach($roleId);
        }

        return $user;
    }

    protected function tokenFor(PenggunaSistem $user): string
    {
        return $user->createToken('api-test-token')->plainTextToken;
    }
}
