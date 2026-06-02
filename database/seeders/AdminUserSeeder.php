<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);

        $admin = User::firstOrCreate(
            ['email' => 'admin@myperfectstitch.com'],
            ['name' => 'Administrator', 'password' => Hash::make('admin1234')]
        );
        $admin->assignRole('admin');
    }
}
