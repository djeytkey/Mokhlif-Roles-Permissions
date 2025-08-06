<?php

return [
    /*
    |--------------------------------------------------------------------------
    | WooCommerce Database Connection
    |--------------------------------------------------------------------------
    |
    | This is the database connection that will be used to connect to your
    | WooCommerce database. Make sure this connection is configured in
    | your database.php config file.
    |
    */
    'database_connection' => env('WOOCOMMERCE_DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | WooCommerce Database Prefix
    |--------------------------------------------------------------------------
    |
    | This is the table prefix used by your WooCommerce installation.
    | Default is 'wp_' but you can change it if needed.
    |
    */
    'table_prefix' => env('WOOCOMMERCE_TABLE_PREFIX', 'wp_'),

    /*
    |--------------------------------------------------------------------------
    | Default Guard
    |--------------------------------------------------------------------------
    |
    | This is the default guard that will be used for authentication.
    |
    */
    'guard' => env('WOOCOMMERCE_GUARD', 'web'),

    /*
    |--------------------------------------------------------------------------
    | Default Roles
    |--------------------------------------------------------------------------
    |
    | These are the default roles that will be created when running
    | the roles:sync command.
    |
    */
    'default_roles' => [
        'admin' => [
            'display_name' => 'Admin',
            'description' => 'Full access to all features',
        ],
        'customer_service_agent' => [
            'display_name' => 'Customer Service Agent',
            'description' => 'View and manage customer orders',
        ],
        'customer_service_manager' => [
            'display_name' => 'Customer Service Manager',
            'description' => 'Oversee customer service agents',
        ],
        'accountant_agent' => [
            'display_name' => 'Accountant Agent',
            'description' => 'View financial data',
        ],
        'accountant_manager' => [
            'display_name' => 'Accountant Manager',
            'description' => 'Oversee accounting agents',
        ],
        'warehouse_manager' => [
            'display_name' => 'Warehouse Manager',
            'description' => 'Oversee inventory operations',
        ],
        'warehouse_agent' => [
            'display_name' => 'Warehouse Agent',
            'description' => 'Manage stock and view orders',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Permissions
    |--------------------------------------------------------------------------
    |
    | These are the default permissions that will be created when running
    | the permissions:sync command.
    |
    */
    'default_permissions' => [
        // Admin permissions
        'manage-users' => [
            'display_name' => 'Manage Users',
            'description' => 'Create, edit, and delete users',
        ],
        'manage-roles' => [
            'display_name' => 'Manage Roles',
            'description' => 'Create, edit, and delete roles',
        ],
        'manage-permissions' => [
            'display_name' => 'Manage Permissions',
            'description' => 'Create, edit, and delete permissions',
        ],
        'view-reports' => [
            'display_name' => 'View Reports',
            'description' => 'Access to all WooCommerce reports',
        ],
        
        // Customer Service permissions
        'view-orders' => [
            'display_name' => 'View Orders',
            'description' => 'View customer orders',
        ],
        'manage-orders' => [
            'display_name' => 'Manage Orders',
            'description' => 'Update order status and details',
        ],
        'view-customers' => [
            'display_name' => 'View Customers',
            'description' => 'View customer information',
        ],
        'manage-customers' => [
            'display_name' => 'Manage Customers',
            'description' => 'Update customer information',
        ],
        
        // Financial permissions
        'view-financial-data' => [
            'display_name' => 'View Financial Data',
            'description' => 'View financial reports and data',
        ],
        'manage-financial-data' => [
            'display_name' => 'Manage Financial Data',
            'description' => 'Update financial information',
        ],
        'view-transactions' => [
            'display_name' => 'View Transactions',
            'description' => 'View transaction history',
        ],
        
        // Warehouse permissions
        'view-inventory' => [
            'display_name' => 'View Inventory',
            'description' => 'View inventory levels',
        ],
        'manage-inventory' => [
            'display_name' => 'Manage Inventory',
            'description' => 'Update inventory levels',
        ],
        'view-stock' => [
            'display_name' => 'View Stock',
            'description' => 'View stock information',
        ],
        'manage-stock' => [
            'display_name' => 'Manage Stock',
            'description' => 'Update stock levels',
        ],
        
        // Team management permissions
        'manage-team' => [
            'display_name' => 'Manage Team',
            'description' => 'Manage team members',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Route Prefix
    |--------------------------------------------------------------------------
    |
    | This is the prefix that will be used for all package routes.
    |
    */
    'route_prefix' => env('WOOCOMMERCE_ROUTE_PREFIX', 'admin/woorolemanager'),

    /*
    |--------------------------------------------------------------------------
    | Middleware
    |--------------------------------------------------------------------------
    |
    | These are the middleware that will be applied to package routes.
    |
    */
    'middleware' => [
        'web',
        'auth',
    ],

    /*
    |--------------------------------------------------------------------------
    | Views Path
    |--------------------------------------------------------------------------
    |
    | This is the path where the package views will be published.
    |
    */
    'views_path' => 'vendor.woorolemanager',
]; 