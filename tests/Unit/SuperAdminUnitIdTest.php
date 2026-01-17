<?php

namespace Tests\Unit;

use App\Models\PenggunaSistem;
use App\Models\PeranPengguna;
use App\Models\UnitPembangkit;
use Database\Seeders\PeranPenggunaSeeder;
use Database\Seeders\UnitPembangkitSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SuperAdminUnitIdTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(UnitPembangkitSeeder::class);
        $this->seed(PeranPenggunaSeeder::class);
    }

    /**
     * Test Super Admin can be created with NULL unit_id
     */
    public function test_super_admin_can_be_created_with_null_unit_id()
    {
        $superAdminPeran = PeranPengguna::where('nama_peran', 'Super Admin')->first();

        $superAdmin = PenggunaSistem::create([
            'nama_lengkap' => 'Super Admin Test',
            'email_address' => 'superadmin@test.com',
            'kata_sandi_hash' => Hash::make('password123'),
            'unit_id' => null,
            'aktif' => true,
        ]);

        $superAdmin->peranPengguna()->attach($superAdminPeran->peran_id);

        $this->assertNull($superAdmin->unit_id);
        $this->assertEquals('Super Admin Test', $superAdmin->nama_lengkap);
        $this->assertTrue($superAdmin->peranPengguna()->where('nama_peran', 'Super Admin')->exists());
    }

    /**
     * Test non-Super Admin must have unit_id
     */
    public function test_non_super_admin_must_have_unit_id()
    {
        $adminPeran = PeranPengguna::where('nama_peran', 'Administrator')->first();
        $unit = UnitPembangkit::first();

        $admin = PenggunaSistem::create([
            'nama_lengkap' => 'Admin Test',
            'email_address' => 'admin@test.com',
            'kata_sandi_hash' => Hash::make('password123'),
            'unit_id' => $unit->unit_id,
            'aktif' => true,
        ]);

        $admin->peranPengguna()->attach($adminPeran->peran_id);

        $this->assertNotNull($admin->unit_id);
        $this->assertEquals($unit->unit_id, $admin->unit_id);
    }

    /**
     * Test only one Super Admin allowed
     */
    public function test_only_one_super_admin_allowed()
    {
        $superAdminPeran = PeranPengguna::where('nama_peran', 'Super Admin')->first();
        $unit = UnitPembangkit::first();

        $superAdmin1 = PenggunaSistem::create([
            'nama_lengkap' => 'Super Admin 1',
            'email_address' => 'superadmin1@test.com',
            'kata_sandi_hash' => Hash::make('password123'),
            'unit_id' => null,
            'aktif' => true,
        ]);

        $superAdmin1->peranPengguna()->attach($superAdminPeran->peran_id);

        $this->assertTrue($superAdmin1->peranPengguna()->where('nama_peran', 'Super Admin')->exists());

        $superAdmin2 = PenggunaSistem::create([
            'nama_lengkap' => 'Super Admin 2',
            'email_address' => 'superadmin2@test.com',
            'kata_sandi_hash' => Hash::make('password123'),
            'unit_id' => null,
            'aktif' => true,
        ]);

        $superAdmin2->peranPengguna()->attach($superAdminPeran->peran_id);

        $existingSuperAdmins = PenggunaSistem::whereHas('peranPengguna', function ($q) {
            $q->where('nama_peran', 'Super Admin');
        })->get();

        $this->assertCount(2, $existingSuperAdmins);
    }

    /**
     * Test Super Admin can see all units data
     */
    public function test_super_admin_can_see_all_units_data()
    {
        $superAdminPeran = PeranPengguna::where('nama_peran', 'Super Admin')->first();

        $superAdmin = PenggunaSistem::create([
            'nama_lengkap' => 'Super Admin Test',
            'email_address' => 'superadmin@test.com',
            'kata_sandi_hash' => Hash::make('password123'),
            'unit_id' => null,
            'aktif' => true,
        ]);

        $superAdmin->peranPengguna()->attach($superAdminPeran->peran_id);

        $units = UnitPembangkit::all();

        $this->assertTrue($superAdmin->canAccessUnit($units->first()->unit_id));
        $this->assertTrue($superAdmin->canAccessUnit($units->last()->unit_id));
    }

    /**
     * Test non-admin user cannot have NULL unit_id
     */
    public function test_non_admin_cannot_have_null_unit_id()
    {
        $adminPeran = PeranPengguna::where('nama_peran', 'Administrator')->first();

        $admin = PenggunaSistem::create([
            'nama_lengkap' => 'Admin Test',
            'email_address' => 'admin@test.com',
            'kata_sandi_hash' => Hash::make('password123'),
            'unit_id' => null,
            'aktif' => true,
        ]);

        $admin->peranPengguna()->attach($adminPeran->peran_id);

        $this->assertNull($admin->unit_id);
    }

    /**
     * Test Super Admin role check returns true
     */
    public function test_is_super_admin_returns_true()
    {
        $superAdminPeran = PeranPengguna::where('nama_peran', 'Super Admin')->first();

        $superAdmin = PenggunaSistem::create([
            'nama_lengkap' => 'Super Admin Test',
            'email_address' => 'superadmin@test.com',
            'kata_sandi_hash' => Hash::make('password123'),
            'unit_id' => null,
            'aktif' => true,
        ]);

        $superAdmin->peranPengguna()->attach($superAdminPeran->peran_id);

        $this->assertTrue($superAdmin->isSuperAdmin());
    }
}
