<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Str;

class ModelPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function before($user, $ability)
    {
        if ($user->super_admin) {
            return true;
        }
    }

    public function __call($name, $arguments)
    {
        // $this->authorize('view', Product::class); >> and ProductPolicy class not defined methods yet

        $class_name = str_replace('Policy', '', class_basename(get_class($this))); // 'App\Policies\ProductPolicy'  >>> 'Product'

        $class_name = Str::plural(Str::lower($class_name)); // 'products'

        if ($name = 'viewAny') {
            $name = 'view';
        }

        $ability = $class_name . '.' . $name; // 'products.view'
        $user = $arguments[0]; // $user // current authenticated user is the first argument 

        if (isset($arguments[1])) {
            $model = $arguments[1]; // $product
            if ($model->store_id !== $user->store_id) {
                return false;
            }
        }
        // dd($class_name, $name, $ability, $user);
        return $user->hasAbility($ability); // $user->hasAbility('products.view')
    }
}
