<?php

namespace App\Support;

class AbilityRegistry
{

    // public static function all(): array
    // {
    //     return include base_path('data/abilities.php');
    // }

    public static function load(): array
    {
        return [

            'categories.view' => __('View Categories'),
            'categories.create' => __('Create Categories'),
            'categories.update' => __('Update Categories'),
            'categories.delete' => __('Delete Categories'),

            'products.view' => __('View Products'),
            'products.create' => __('Create Products'),
            'products.update' => __('Update Products'),
            'products.delete' => __('Delete Products'),
            'products.restore' => __('Restore Products'),
            'products.force-delete' => __('Force Delete Products'),

            'orders.view' => __('View Orders'),
            'orders.create' => __('Create Orders'),
            'orders.update' => __('Update Orders'),
            'orders.delete' => __('Delete Orders'),

            'users.view' => __('View Users'),
            'users.create' => __('Create Users'),
            'users.update' => __('Update Users'),
            'users.delete' => __('Delete Users'),

            'admins.view' => __('View Admins'),
            'admins.create' => __('Create Admins'),
            'admins.update' => __('Update Admins'),
            'admins.delete' => __('Delete Admins'),

            'roles.view' => __('View Roles'),
            'roles.create' => __('Create Roles'),
            'roles.update' => __('Update Roles'),
            'roles.delete' => __('Delete Roles'),
            'roles.restore' => __('Restore Roles'),
            'roles.force-delete' => __('Force Delete Roles'),

            'permissions.view' => __('View Permissions'),
            'permissions.create' => __('Create Permissions'),
            'permissions.update' => __('Update Permissions'),
            'permissions.delete' => __('Delete Permissions'),

            'stores.view' => __('View Stores'),
            'stores.create' => __('Create Stores'),
            'stores.update' => __('Update Stores'),
            'stores.delete' => __('Delete Stores'),


        ];
    }
}
