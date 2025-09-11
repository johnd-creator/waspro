<?php

namespace App\Console\Commands;

use App\Models\UnitPembangkit;
use App\Models\PenggunaSistem;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateTestUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:test-user {--email=admin@example.com} {--password=password} {--name=Administrator}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a test user for the application';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->option('email');
        $password = $this->option('password');
        $name = $this->option('name');
        
        // Get the first unit or create one if none exists
        $unit = UnitPembangkit::first();
        
        if (!$unit) {
            $this->error('No unit found. Please run the seeders first.');
            return 1;
        }
        
        // Check if user already exists
        $existingUser = PenggunaSistem::where('email_address', $email)->first();
        
        if ($existingUser) {
            $this->info("User with email {$email} already exists.");
            return 0;
        }
        
        // Create the user
        $user = PenggunaSistem::create([
            'nama_lengkap' => $name,
            'email_address' => $email,
            'kata_sandi_hash' => Hash::make($password),
            'aktif' => true,
            'unit_id' => $unit->unit_id,
        ]);
        
        $this->info("Test user created successfully with email: {$email}");
        return 0;
    }
}
