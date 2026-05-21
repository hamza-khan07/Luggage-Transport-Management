<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if admin already exists to prevent duplicate errors
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin System',
                'phone' => '00000000000',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        if ($admin->role !== 'admin') {
            $admin->update(['role' => 'admin']);
        }
        
        $this->command->info('Admin user verified with admin role.');
    }
}
