<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\RoleAbility;
use App\Support\AbilityRegistry;
use Illuminate\Database\Seeder;

class AbilitySeeder extends Seeder
{
    public function run()
    {
        $this->command->info('Assigning Abilities...');

        $role = Role::where('name', 'super-admin')->first();

        if (!$role) {
            $this->command->error('   ✗ Super Admin role not found!');
            return;
        }

        $abilities = AbilityRegistry::load();
        $totalAbilities = count($abilities);

        $this->command->info('   Found ' . $totalAbilities . ' abilities');

        // Delete existing
        RoleAbility::where('role_id', $role->id)->delete();

        // Progress bar
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
    }
}
