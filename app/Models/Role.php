<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name'];






    // ==========[ Relationships ]========== //
    public function abilities()
    {
        // one to many || one role has many abilities
        // ['role_id', 'ability', 'type']
        return $this->hasMany(RoleAbility::class, 'role_id', 'id'); // ->pluck('type', 'ability');
    }








    public static function createWithAbilities(Request $request)
    {
        DB::beginTransaction();

        try {
            $role = Role::create([
                'name' => $request->post('name'),
            ]);
            // $request->post('abilities') returns Like >>> ['abilities'=> [ 'categories.view' => 'allow', 'products.create' => 'deny', .... ]]
            foreach ($request->post('abilities') as $ability => $value) {
                RoleAbility::create([
                    'role_id' => $role->id,
                    'ability' => $ability,
                    'type' => $value,
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $role;
    }

    public  function updateWithAbilities(Request $request)
    {
        DB::beginTransaction();

        try {
            $this->update([
                'name' => $request->post('name'),
            ]);

            foreach ($request->post('abilities') as $ability => $value) {
                // if 'role_id' and 'ability' already exist, it will update the 'type' to '$value', otherwise it will create a new record include 'role_id', 'ability', 'type' together
                RoleAbility::updateOrCreate([
                    'role_id' => $this->id,
                    'ability' => $ability,
                ], [
                    'type' => $value,
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $this;
    }
}
