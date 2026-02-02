<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Role;
use App\Models\RoleAbility;
use App\Support\AbilityRegistry;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('');
        $this->command->info('Starting Super Admin Setup...');
        $this->command->info('=====================================');

        DB::beginTransaction();

        try {
            // Step 1: Create Super Admin Role
            $this->command->info('');
            $this->command->warn('Step 1/4: Creating Super Admin Role...');

            $role = Role::firstOrCreate(
                ['name' => 'super-admin'],
                [
                    'name' => 'super-admin',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            $this->command->info('   ✓ Role created: ' . $role->name . ' (ID: ' . $role->id . ')');

            // Step 2: Load All Abilities
            $this->command->info('');
            $this->command->warn('Step 2/4: Loading Abilities...');

            $abilities = AbilityRegistry::load();
            $totalAbilities = count($abilities);

            $this->command->info('   ✓ Found ' . $totalAbilities . ' abilities');

            // Step 3: Assign All Abilities
            $this->command->info('');
            $this->command->warn('Step 3/4: Assigning Abilities to Role...');

            // Delete existing abilities for this role
            RoleAbility::where('role_id', $role->id)->delete();

            $bar = $this->command->getOutput()->createProgressBar($totalAbilities);
            $bar->start();

            $inserted = 0;
            foreach ($abilities as $ability => $label) {
                RoleAbility::create([
                    'role_id' => $role->id,
                    'ability' => $ability,
                    'type' => 'allow',
                ]);
                $inserted++;
                $bar->advance();
            }

            $bar->finish();
            $this->command->info('');
            $this->command->info('   ✓ Assigned ' . $inserted . ' abilities');

            // Step 4: Create Super Admin User
            $this->command->info('');
            $this->command->warn('Step 4/4: Creating Super Admin User...');

            $admin = Admin::firstOrCreate(
                ['email' => 'owner@ahmed-store.com'],
                [
                    'name' => 'Ahmed Ashrad',
                    'email' => 'owner@ahmed-store.com',
                    'username' => 'a7mad25',
                    'phone_number' => '+201234567890',
                    'password' => Hash::make('password'),
                    'super_admin' => 1,
                    'status' => 'active',
                    // 'email_verified_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            $this->command->info('   ✓ Admin created: ' . $admin->name . ' (ID: ' . $admin->id . ')');

            // Attach role to admin
            if (!$admin->roles()->where('role_id', $role->id)->exists()) {
                $admin->roles()->attach($role->id, [
                    'authorizable_type' => 'App\Models\Admin',
                    'authorizable_id' => $admin->id,
                ]);
                $this->command->info('   ✓ Role attached to admin');
            }

            DB::commit();

            // Success Summary
            $this->command->info('');
            $this->command->info('=====================================');
            $this->command->info('Super Admin Setup Complete!');
            $this->command->info('=====================================');
            $this->command->info('');

            // Display credentials table
            $headers = ['Field', 'Value'];
            $data = [
                ['Email', $admin->email],
                ['Username', $admin->username],
                ['Phone', $admin->phone_number],
                ['Password', 'password'],
                ['Role', $role->name],
                ['Abilities', $inserted . ' permissions'],
            ];

            $this->command->table($headers, $data);
        } catch (\Exception $e) {
            DB::rollBack();

            $this->command->error('');
            $this->command->error(' Error: ' . $e->getMessage());
            $this->command->error('');

            throw $e;
        }
    }
}
