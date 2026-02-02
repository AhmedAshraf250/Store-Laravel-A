<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('📝 Creating Admin Users...');

        $role = Role::where('name', 'super-admin')->first();

        if (!$role) {
            $this->command->error('   ✗ Super Admin role not found!');
            return;
        }

        // Super Admin
        $admin = Admin::firstOrCreate(
            ['email' => 'owner@ahmed-store.com'],
            [
                'name' => 'Ahmed Ashraf',
                'email' => 'owner@ahmed-store.com',
                'username' => 'ahmedika',
                'phone_number' => '+201234567890',
                'password' => Hash::make('password'),
                'super_admin' => 1,
                'status' => 'active',
                // 'email_verified_at' => now(),
            ]
        );

        // Attach role
        if (!$admin->roles()->where('role_id', $role->id)->exists()) {
            $admin->roles()->attach($role->id, [
                'authorizable_type' => 'App\Models\Admin',
                'authorizable_id' => $admin->id,
            ]);
        }

        $this->command->info('   ✓ ' . $admin->name . ' (' . $admin->email . ')');
    }
}
