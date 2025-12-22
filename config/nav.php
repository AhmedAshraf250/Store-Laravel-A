<?php
// اى عنصر موجود فى السايد بار او الناف اهم 3 اشياء بطبيعة الحال لابد من وجودهم فى اى عنصر موجود
// اسمه والرابط الخاص به وايقون تدل عليه مثلا
return [
    [
        'icon' => 'nav-icon fas fa-tachometer-alt',
        'route' => 'dashboard.dashboard',
        'title' => 'Dashboard',
        'active' => 'dashboard.dashboard'
    ],
    [
        'icon' => 'nav-icon fas fa-th',
        'route' => 'dashboard.categories.index',
        'title' => 'Categories',
        'badge' => 'new',
        'active' => 'dashboard.categories.*',
        'ability' => 'categories.view' // to show this nav item only for users who have this ability
    ],
    [
        'icon' => 'nav-icon fas fa-edit',
        'route' => 'dashboard.categories.index',
        'title' => 'Stores',
        'active' => 'dashboard.stores.*',
        'ability' => 'stores.view'
    ],
    [
        'icon' => 'nav-icon fas fa-columns',
        'route' => 'dashboard.products.index',
        'title' => 'Products',
        'active' => 'dashboard.products.*',
        'ability' => 'products.view',
    ],
    [
        'icon' => 'nav-icon fas fa-columns',
        'route' => 'dashboard.categories.index',
        'title' => 'Orders',
        'active' => 'dashboard.orders.*',
        'ability' => 'orders.view',
    ],
    [
        'icon' => 'nav-icon fas fa-shield-alt',
        'route' => 'dashboard.roles.index',
        'title' => 'Roles',
        'active' => 'dashboard.roles.*',
        'ability' => 'roles.view',
    ],
    [
        'icon' => 'nav-icon fas fa-users',
        'route' => 'dashboard.users.index',
        'title' => 'Users',
        'active' => 'dashboard.users.*',
        'ability' => 'users.view',
    ],
    [
        'icon' => 'nav-icon fas fa-user-shield',
        'route' => 'dashboard.admins.index',
        'title' => 'Admins',
        'active' => 'dashboard.admins.*',
        'ability' => 'admins.view',
    ],
];

/**
 * the idea here is that if I want to add a new item to the nav-bar all I have to do is to add it here and define its properties like (icons-route-title-etc)
 * instead of going to the view file and defining and designing this new part,
 * then the responsibility of the component associated here is to display the nav for example.
 * look => 'app\View\Components\Nav.php' & 'resources\views\components\nav.blade.php'
 */
