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
        'active' => 'dashboard.categories.*'
    ],
    [
        'icon' => 'nav-icon fas fa-edit',
        'route' => 'dashboard.categories.index',
        'title' => 'Stores',
        'active' => 'dashboard.stores.*'
    ],
    [
        'icon' => 'nav-icon fas fa-columns',
        'route' => 'dashboard.products.index',
        'title' => 'Products',
        'active' => 'dashboard.products.*'
    ],
    [
        'icon' => 'nav-icon fas fa-columns',
        'route' => 'dashboard.categories.index',
        'title' => 'Orders',
        'active' => 'dashboard.orders.*'
    ]
];

/**
 * the idea here is that if I want to add a new item to the nav-bar all I have to do is to add it here and define its properties like (icons-route-title-etc)
 * instead of going to the view file and defining and designing this new part,
 * then the responsibility of the component associated here is to display the nav for example.
 * look => 'app\View\Components\Nav.php' & 'resources\views\components\nav.blade.php'
 */
