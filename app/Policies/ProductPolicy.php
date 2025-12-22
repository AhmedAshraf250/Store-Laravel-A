<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy // extends ModelPolicy
{
    use HandlesAuthorization;

    /*
    |------------------------------------------------------------------------------------------------------------
    | Authorization Using Policies
    |------------------------------------------------------------------------------------------------------------
    |
    | Authorization via Policies is performed using the `authorize()` method inside controllers.
    |
    | The first argument passed to `authorize()` is the ability name, which must match a method name inside the policy.
    |
    | The second argument is the model (or model class) that the policy is associated with.
    |
    | Example: // ProductController 
    | $this->authorize('viewAny', Product::class);
    | $this->authorize('update', $product);
    |
    */
    /*
    |------------------------------------------------------------------------------------------------------------
    | Policy Naming Convention
    |------------------------------------------------------------------------------------------------------------
    |
    | If the policy follows Laravel’s naming convention:
    |   Product       → ProductPolicy
    |
    | Laravel will automatically discover and register the policy.
    | In this case, there is NO need to manually register it inside AuthServiceProvider::$policies.
    |
    | If you use a custom policy name or structure, you must register it manually in AuthServiceProvider.
    |
    */
    /*
    |------------------------------------------------------------------------------------------------------------
    | Controller Usage Example
    |------------------------------------------------------------------------------------------------------------
    |
    | class ProductsController extends Controller
    | {
    |     public function index()
    |     {
    |         // Checks the "viewAny" method in ProductPolicy
    |         $this->authorize('viewAny', Product::class);
    |     }
    |
    |     public function edit(Product $product)
    |     {
    |         // Checks the "update" method in ProductPolicy
    |         $this->authorize('update', $product);
    |     }
    | }
    |
    */
    /*
    |------------------------------------------------------------------------------------------------------------
    | Blade Usage Example
    |------------------------------------------------------------------------------------------------------------
    |
    | @can('viewAny', App\Models\Product::class)
    |     // User can view products
    | @endcan
    |
    | @can('update', $product)
    |     // User can update this product
    | @endcan
    |
    */
    /*
    |------------------------------------------------------------------------------------------------------------
    | Policies vs Gates
    |------------------------------------------------------------------------------------------------------------
    |
    | Policies:
    | - Organized per model
    | - Contain standard methods like:
    |   viewAny, view, create, update, delete
    | - Automatically receive the authenticated user
    |
    | Gates:
    | - Defined manually in AuthServiceProvider
    | - Not tied to a specific model
    |
    | Gate Definition Example:
    | Gate::define('category.view', function (User $user) {
    |     return $user->isAdmin();
    | });
    |
    | Gate Usage (Controller):
    | Gate::authorize('category.view');
    |
    | Gate Usage (Blade):
    | @can('category.view')
    |     ...
    | @endcan
    |
    */



    // public function before($user, $ability)
    // {
    //     if ($user->super_admin) {
    //         return true;
    //     }
    // }


    public function before($user, $ability)
    {
        if ($user->super_admin) {
            return true;
        }
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny($user)
    {
        return $user->hasAbility('products.view');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view($user, Product $product)
    {
        return $user->hasAbility('products.view') && $user->store_id == $product->store_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create($user)
    {
        return $user->hasAbility('products.create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update($user, Product $product)
    {
        return $user->hasAbility('products.update') && $user->store_id == $product->store_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete($user, Product $product)
    {
        return $user->hasAbility('products.delete') && $user->store_id == $product->store_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore($user, Product $product)
    {
        return $user->hasAbility('products.restore') && $user->store_id == $product->store_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete($user, Product $product)
    {
        return $user->hasAbility('products.force-delete') && $user->store_id == $product->store_id;
    }
}
