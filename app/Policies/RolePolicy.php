<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy // extends ModelPolicy
{
    use HandlesAuthorization;

    // i removed User $user from all functions to make it work dynamically bettwen User and Admin //
    public function viewAny($user)
    {
        // return false;
        return $user->hasAbility('roles.view');
    }


    public function view($user, Role $role)
    {
        // return true;
        return $user->hasAbility('roles.view');
    }

    public function create($user)
    {
        return $user->hasAbility('roles.create');
    }

    public function update($user, Role $role)
    {
        return $user->hasAbility('roles.update');
    }


    public function delete($user, Role $role)
    {
        return $user->hasAbility('roles.delete');
    }


    public function restore($user, Role $role)
    {
        return $user->hasAbility('roles.restore');
    }


    public function forceDelete($user, Role $role)
    {
        return $user->hasAbility('roles.force-delete');
    }
}
