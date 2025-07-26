<?php

return [
    /*
    |--------------------------------------------------------------------------
    | PMS Hotel Package Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for the PMS Hotel package.
    |
    */

    'modules' => [
        'room_rate_rules' => [
            'name' => 'Room Rate Rules',
            'slug' => 'room_rate_rules',
            'permissions' => ['view', 'create', 'edit', 'delete', 'export', 'import'],
            'menu_icon' => 'fas fa-bed',
            'menu_order' => 1,
        ],
        'foods' => [
            'name' => 'Foods',
            'slug' => 'foods',
            'permissions' => ['view', 'create', 'edit', 'delete', 'export', 'import'],
            'menu_icon' => 'fas fa-utensils',
            'menu_order' => 2,
        ],
        'food_types' => [
            'name' => 'Food Types',
            'slug' => 'food_types',
            'permissions' => ['view', 'create', 'edit', 'delete', 'export', 'import'],
            'menu_icon' => 'fas fa-list',
            'menu_order' => 3,
        ],
        'dishes' => [
            'name' => 'Platillos',
            'slug' => 'dishes',
            'permissions' => ['view', 'create', 'edit', 'delete', 'export', 'import'],
            'menu_icon' => 'fas fa-book-open',
            'menu_order' => 4,
        ],
        'departments' => [
            'name' => 'Departments',
            'slug' => 'departments',
            'permissions' => ['view', 'create', 'edit', 'delete', 'export', 'import'],
            'menu_icon' => 'fas fa-building',
            'menu_order' => 5,
        ],
        'decorations' => [
            'name' => 'Decorations',
            'slug' => 'decorations',
            'permissions' => ['view', 'create', 'edit', 'delete', 'export', 'import'],
            'menu_icon' => 'fas fa-palette',
            'menu_order' => 6,
        ],
        'events' => [
            'name' => 'Events',
            'slug' => 'events',
            'permissions' => ['view', 'create', 'edit', 'delete', 'export', 'import'],
            'menu_icon' => 'fas fa-calendar-alt',
            'menu_order' => 7,
        ],
        'restaurants' => [
            'name' => 'Restaurants',
            'slug' => 'restaurants',
            'permissions' => ['view', 'create', 'edit', 'delete', 'export', 'import'],
            'menu_icon' => 'fas fa-store',
            'menu_order' => 8,
        ],
        'desserts' => [
            'name' => 'Desserts',
            'slug' => 'desserts',
            'permissions' => ['view', 'create', 'edit', 'delete', 'export', 'import'],
            'menu_icon' => 'fas fa-ice-cream',
            'menu_order' => 9,
        ],
        'beverages' => [
            'name' => 'Beverages',
            'slug' => 'beverages',
            'permissions' => ['view', 'create', 'edit', 'delete', 'export', 'import'],
            'menu_icon' => 'fas fa-glass-martini',
            'menu_order' => 10,
        ],
        'room_changes' => [
            'name' => 'Room Changes',
            'slug' => 'room_changes',
            'permissions' => ['view', 'create', 'edit', 'delete', 'export', 'import'],
            'menu_icon' => 'fas fa-exchange-alt',
            'menu_order' => 11,
        ],
        'special_requirements' => [
            'name' => 'Special Requirements',
            'slug' => 'special_requirements',
            'permissions' => ['view', 'create', 'edit', 'delete', 'export', 'import'],
            'menu_icon' => 'fas fa-star',
            'menu_order' => 12,
        ],
        'restaurant_availability' => [
            'name' => 'Restaurant Availability',
            'slug' => 'restaurant_availability',
            'permissions' => ['view', 'create', 'edit', 'delete', 'export', 'import'],
            'menu_icon' => 'fas fa-clock',
            'menu_order' => 13,
        ],
        'restaurant_reservations' => [
            'name' => 'Restaurant Reservations',
            'slug' => 'restaurant_reservations',
            'permissions' => ['view', 'create', 'edit', 'delete', 'export', 'import'],
            'menu_icon' => 'fas fa-calendar-check',
            'menu_order' => 14,
        ],
        'reservations' => [
            'name' => 'Reservations',
            'slug' => 'reservations',
            'permissions' => ['view', 'create', 'edit', 'delete', 'export', 'import'],
            'menu_icon' => 'fas fa-bookmark',
            'menu_order' => 15,
        ],
        'courtesy_dinners' => [
            'name' => 'Courtesy Dinners',
            'slug' => 'courtesy_dinners',
            'permissions' => ['view', 'create', 'edit', 'delete', 'export', 'import'],
            'menu_icon' => 'fas fa-gift',
            'menu_order' => 16,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Database Configuration
    |--------------------------------------------------------------------------
    |
    | Database connection and table prefix configuration.
    |
    */
    'database' => [
        'connection' => 'tenant',
        'table_prefix' => 'pms_',
    ],

    /*
    |--------------------------------------------------------------------------
    | Auth Package Integration
    |--------------------------------------------------------------------------
    |
    | Configuration for integration with kaelytechnology/auth-package.
    |
    */
    'auth_integration' => [
        'module_slug' => 'pms_hotel',
        'module_name' => 'PMS Hotel',
        'auto_register_permissions' => true,
        'auto_register_menus' => true,
    ],
];