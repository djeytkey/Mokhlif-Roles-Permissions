# WooRoleManager - Laravel 12 Package

A comprehensive Laravel 12 package for WooCommerce Role & Permission Management with direct database integration using Eloquent ORM. **This package is designed to work with your existing User model and controllers.**

## Features

- **Works with Existing Models**: Integrates with your existing User model and controllers
- **Direct WooCommerce Database Integration**: Uses Eloquent ORM to connect directly to WooCommerce database
- **Role-Based Access Control**: Complete role and permission management system
- **Dynamic Sidebar Navigation**: Sidebar changes based on user roles
- **Modern UI**: Beautiful, responsive interface with Tailwind CSS
- **Laravel 12 Compatible**: Built specifically for Laravel 12
- **Artisan Commands**: Built-in commands for syncing roles and permissions
- **Middleware Support**: Custom middleware for role and permission checking

## Integration with Existing Models

This package is designed to work alongside your existing User model and controllers. It adds role functionality through a trait that can be mixed into your existing User model.

### Adding Role Functionality to Your User Model

Add the `HasRoles` trait to your existing User model:

```php
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use BoukjijTarik\WooRoleManager\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;

    // Your existing User model code...
}
```

### Using with Existing Controllers

The package provides additional controllers for role management, but you can continue using your existing controllers. The role functionality will be available through the trait methods.

## Default Roles

| Role | Description |
|------|-------------|
| Admin | Full access to all features |
| Customer Service Agent | View/manage customer orders |
| Customer Service Manager | Oversee CS agents |
| Accountant Agent | View financial data |
| Accountant Manager | Oversee accounting agents |
| Warehouse Manager | Oversee inventory operations |
| Warehouse Agent | Manage stock, view orders |

## Installation

### 1. Install via Composer

```bash
composer require boukjijtarik/woorolemanager
```

### 2. Publish Configuration

```bash
php artisan vendor:publish --tag=woorolemanager-config
```

### 3. Configure Database Connection

Add your WooCommerce database connection to `config/database.php`:

```php
'connections' => [
    // ... other connections
    
    'woocommerce' => [
        'driver' => 'mysql',
        'host' => env('WOOCOMMERCE_DB_HOST', '127.0.0.1'),
        'port' => env('WOOCOMMERCE_DB_PORT', '3306'),
        'database' => env('WOOCOMMERCE_DB_DATABASE', 'forge'),
        'username' => env('WOOCOMMERCE_DB_USERNAME', 'forge'),
        'password' => env('WOOCOMMERCE_DB_PASSWORD', ''),
        'unix_socket' => env('WOOCOMMERCE_DB_SOCKET', ''),
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => env('WOOCOMMERCE_TABLE_PREFIX', 'wp_'),
        'prefix_indexes' => true,
        'strict' => true,
        'engine' => null,
        'options' => extension_loaded('pdo_mysql') ? array_filter([
            PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
        ]) : [],
    ],
],
```

### 4. Add Environment Variables

Add these to your `.env` file:

```env
WOOCOMMERCE_DB_CONNECTION=woocommerce
WOOCOMMERCE_DB_HOST=127.0.0.1
WOOCOMMERCE_DB_PORT=3306
WOOCOMMERCE_DB_DATABASE=your_woocommerce_db
WOOCOMMERCE_DB_USERNAME=your_username
WOOCOMMERCE_DB_PASSWORD=your_password
WOOCOMMERCE_TABLE_PREFIX=wp_
WOOCOMMERCE_GUARD=web
WOOCOMMERCE_ROUTE_PREFIX=admin/woorolemanager
```

### 5. Run Migrations

```bash
php artisan migrate
```

### 6. Sync Default Roles and Permissions

```bash
php artisan roles:sync
php artisan permissions:sync
```

## Usage

### Basic Usage

The package automatically registers routes under `/admin/woorolemanager`. Access the dashboard at:

```
http://your-app.com/admin/woorolemanager
```

### Role Management

```php
// Check if user has a role
$user->hasRole('admin');

// Check if user has any of the given roles
$user->hasAnyRole(['admin', 'customer_service_manager']);

// Check if user has all of the given roles
$user->hasAllRoles(['admin', 'customer_service_manager']);

// Assign role to user
$user->assignRole('admin');

// Remove role from user
$user->removeRole('admin');

// Sync roles (replace all roles)
$user->syncRoles(['admin', 'customer_service_agent']);
```

### Permission Management

```php
// Check if user has a permission
$user->hasPermission('manage-users');

// Check if user has any of the given permissions
$user->hasAnyPermission(['manage-users', 'manage-roles']);
```

### Middleware Usage

```php
// Route middleware
Route::middleware(['check.role:admin'])->group(function () {
    // Admin only routes
});

Route::middleware(['check.permission:manage-users'])->group(function () {
    // Routes for users with manage-users permission
});
```

### Blade Directives

```blade
@role('admin')
    <!-- Admin only content -->
@endrole

@permission('manage-users')
    <!-- Content for users with manage-users permission -->
@endpermission
```

### Integration with Your Existing Dashboard

You can integrate the role-based navigation into your existing dashboard:

```php
use BoukjijTarik\WooRoleManager\WooRoleManager;

class DashboardController extends Controller
{
    protected $wooRoleManager;

    public function __construct(WooRoleManager $wooRoleManager)
    {
        $this->wooRoleManager = $wooRoleManager;
    }

    public function index()
    {
        $user = auth()->user();
        $navigation = $this->wooRoleManager->getSidebarNavigation($user);
        
        return view('dashboard', compact('navigation'));
    }
}
```

## Dashboard Views

The package provides different dashboard views based on user roles:

- **Admin**: Full dashboard with all features
- **Customer Service Agent**: Order and customer management
- **Customer Service Manager**: Team oversight + CS features
- **Accountant Agent**: Financial data access
- **Accountant Manager**: Financial management + team oversight
- **Warehouse Manager**: Inventory oversight + team management
- **Warehouse Agent**: Stock management

## Sidebar Navigation

The sidebar automatically adapts based on user roles:

### Admin Sidebar
- Dashboard
- Manage Users
- Manage Roles
- Manage Permissions
- WooCommerce Reports

### Customer Service Agent
- Dashboard
- View Orders
- Customer Lookup

### Warehouse Agent
- Dashboard
- Inventory Overview
- Update Stock

## Artisan Commands

### Sync Roles
```bash
php artisan roles:sync
```
Creates default roles if they don't exist.

### Sync Permissions
```bash
php artisan permissions:sync
```
Creates default permissions and assigns them to appropriate roles.

## Configuration

The package configuration is located at `config/woorolemanager.php`. Key options:

- `database_connection`: WooCommerce database connection name
- `table_prefix`: WooCommerce table prefix (default: `wp_`)
- `guard`: Authentication guard to use
- `route_prefix`: Route prefix for package routes
- `default_roles`: Array of default roles to create
- `default_permissions`: Array of default permissions to create

## Security

- All routes are protected by authentication middleware
- Role and permission checks are enforced at middleware level
- Only admins can manage roles and permissions
- CSRF protection enabled on all forms

## Testing

```bash
composer test
```

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For support, please open an issue on GitHub or contact us at tarik.engineer@gmail.com.

## Changelog

### 1.0.0
- Initial release
- Laravel 12 compatibility
- WooCommerce database integration
- Role and permission management
- Dynamic sidebar navigation
- Modern UI with Tailwind CSS
- Artisan commands for syncing
- Middleware for access control
- Integration with existing User models 