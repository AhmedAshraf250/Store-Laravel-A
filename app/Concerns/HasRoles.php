<?php

namespace App\Concerns;

use App\Models\Role;
use Illuminate\Support\Facades\Cache;

trait HasRoles
{
    /**
     * |-----------------------------------------------------------------------|
     * |                         'role_user' table                             |
     * |-----------------------------------------------------------------------|
     * | 'authorizable_type'    | 'authorizable_id'      | 'role_id'           |
     * |-----------------------------------------------------------------------|
     * |  APP\Models\User       |       20               |      4              |
     * |-----------------------------------------------------------------------|
     * |  APP\Models\Admin      |       1                |      1              |
     * |-----------------------------------------------------------------------|
     * |  APP\Models\User       |       21               |      5              |
     * |-----------------------------------------------------------------------|
     * |  APP\Models\User       |       22               |      4              |
     * |-----------------------------------------------------------------------|
     * |  APP\Models\Admin      |       4                |      2              |
     * |-----------------------------------------------------------------------|
     */
    /**
     * |------------------------------------------------|
     * |                  'roles' table                 |
     * |------------------------------------------------|
     * |        'id'            |        'name'         |
     * |------------------------------------------------|
     * |         1              |    Administrator      |
     * |------------------------------------------------|
     * |         2              |      Moderator        |
     * |------------------------------------------------|
     * |         3              |    Shop Owner         |
     * |------------------------------------------------| .....
     */



    // User        ==[calls]-[morphToMany]==>       'role_user' table      ==[return|retrieve]==> Role
    // public function roles()
    // {
    //     // {role_user} >>>> ['authorizable_type','authorizable_id','role_id']
    //     return $this->morphToMany(Role::class, 'authorizable', 'role_user', 'authorizable_id', 'role_id', 'id', 'id');
    // }
    public function roles()
    {
        // [  "select * from `roles` inner join `role_user` on `roles`.`id` = `role_user`.`role_id` 
        // where `role_user`.`authorizable_id` = ? and `role_user`.`authorizable_type` = ? "  ]

        return $this->morphToMany(Role::class, 'authorizable', 'role_user');
    }

    public function hasAbility($ability): bool
    {
        // ===========[ Legacy Implementation ]=========== //
        // // piority to 'deny' over 'allow'
        // $denied = $this->roles()->whereHas('abilities', function ($query) use ($ability) {
        //     $query->where('ability', $ability)->where('type', 'deny');
        // })->exists();
        // if ($denied) {
        //     return false;
        // }

        // // return true;
        // // dd($this->roles());

        // // [  "select * from `role_abilities` where `roles`.`id` = `role_abilities`.`role_id` and `ability` = ? and `type` = ?"  ]
        // // whereHas('relations', function ($query) {}) -- using whereHas to add condition to relation query
        // // has('relation) -- just to check if relation exist or not
        // return $this->roles()->whereHas('abilities', function ($query) use ($ability) {
        //     $query->where('ability', $ability)->where('type', 'allow');
        // })->exists();

        $types = $this->cachedAbilities()->get($ability);

        if (!$types) {
            return false;
        }

        // deny has piority over allow
        if ($types->contains('deny')) {
            return false;
        }

        return $types->contains('allow');
    }


    protected function cachedAbilities()
    {
        $cacheKey = class_basename($this) . "_{$this->id}_abilities";

        $keys = Cache::get('debug_cache_keys', []);
        $keys[] = $cacheKey;
        Cache::put('debug_cache_keys', $keys);

        // Cache::remember($key, $ttl, $callback) >>> if key exists in cache, return it, else execute callback
        return Cache::remember($cacheKey, now()->addMinutes(30), function () {

            return $this->roles()
                ->with('abilities')
                ->get()
                ->flatMap->abilities
                ->groupBy('ability')
                ->map(fn($items) => $items->pluck('type')->unique()->values());
        });
    }



    // if Role or RoleAbility is changed, we need to clear the cached abilities for the user
    // $user->forgetCachedAbilities();
    public function forgetCachedAbilities()
    {
        $cacheKey = class_basename($this) . "_{$this->id}_abilities";
        Cache::forget($cacheKey);
    }
}
