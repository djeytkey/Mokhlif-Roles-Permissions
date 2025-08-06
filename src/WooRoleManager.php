<?php

namespace BoukjijTarik\WooRoleManager;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use BoukjijTarik\WooRoleManager\Models\Role;
use BoukjijTarik\WooRoleManager\Models\Permission;

class WooRoleManager
{
    /**
     * Get all roles.
     */
    public function getRoles()
    {
        return Role::with('permissions')->get();
    }

    /**
     * Get all permissions.
     */
    public function getPermissions()
    {
        return Permission::all();
    }

    /**
     * Assign role to user.
     */
    public function assignRoleToUser($userId, $roleId)
    {
        $userModelClass = config('auth.providers.users.model', \App\Models\User::class);
        $user = $userModelClass::find($userId);
        $role = Role::find($roleId);

        if ($user && $role) {
            $user->roles()->syncWithoutDetaching([$roleId]);
            return true;
        }

        return false;
    }

    /**
     * Remove role from user.
     */
    public function removeRoleFromUser($userId, $roleId)
    {
        $userModelClass = config('auth.providers.users.model', \App\Models\User::class);
        $user = $userModelClass::find($userId);
        
        if ($user) {
            $user->roles()->detach($roleId);
            return true;
        }

        return false;
    }

    /**
     * Assign permission to role.
     */
    public function assignPermissionToRole($roleId, $permissionId)
    {
        $role = Role::find($roleId);
        $permission = Permission::find($permissionId);

        if ($role && $permission) {
            $role->permissions()->syncWithoutDetaching([$permissionId]);
            return true;
        }

        return false;
    }

    /**
     * Get user's dashboard view based on their role.
     */
    public function getUserDashboardView($user = null)
    {
        if (!$user) {
            $user = Auth::user();
        }

        if (!$user) {
            return 'woorolemanager::dashboard.default';
        }

        $role = $user->roles()->first();
        
        if (!$role) {
            return 'woorolemanager::dashboard.default';
        }

        $dashboardViews = [
            'admin' => 'woorolemanager::dashboard.admin',
            'customer_service_agent' => 'woorolemanager::dashboard.cs_agent',
            'customer_service_manager' => 'woorolemanager::dashboard.cs_manager',
            'accountant_agent' => 'woorolemanager::dashboard.acc_agent',
            'accountant_manager' => 'woorolemanager::dashboard.acc_manager',
            'warehouse_manager' => 'woorolemanager::dashboard.wh_manager',
            'warehouse_agent' => 'woorolemanager::dashboard.wh_agent',
        ];

        return $dashboardViews[$role->name] ?? 'woorolemanager::dashboard.default';
    }

    /**
     * Get sidebar navigation based on user's role.
     */
    public function getSidebarNavigation($user = null)
    {
        if (!$user) {
            $user = Auth::user();
        }

        if (!$user) {
            return [];
        }

        $role = $user->roles()->first();
        
        if (!$role) {
            return [];
        }

        $navigation = [
            'admin' => [
                ['name' => 'Dashboard', 'route' => 'woorolemanager.dashboard', 'icon' => 'fas fa-tachometer-alt'],
                ['name' => 'Manage Users', 'route' => 'woorolemanager.users.index', 'icon' => 'fas fa-users'],
                ['name' => 'Manage Roles', 'route' => 'woorolemanager.roles.index', 'icon' => 'fas fa-user-tag'],
                ['name' => 'Manage Permissions', 'route' => 'woorolemanager.permissions.index', 'icon' => 'fas fa-key'],
                ['name' => 'WooCommerce Reports', 'route' => 'woorolemanager.reports', 'icon' => 'fas fa-chart-bar'],
            ],
            'customer_service_agent' => [
                ['name' => 'Dashboard', 'route' => 'woorolemanager.dashboard', 'icon' => 'fas fa-tachometer-alt'],
                ['name' => 'View Orders', 'route' => 'woorolemanager.orders.index', 'icon' => 'fas fa-shopping-cart'],
                ['name' => 'Customer Lookup', 'route' => 'woorolemanager.customers.index', 'icon' => 'fas fa-search'],
            ],
            'customer_service_manager' => [
                ['name' => 'Dashboard', 'route' => 'woorolemanager.dashboard', 'icon' => 'fas fa-tachometer-alt'],
                ['name' => 'View Orders', 'route' => 'woorolemanager.orders.index', 'icon' => 'fas fa-shopping-cart'],
                ['name' => 'Customer Lookup', 'route' => 'woorolemanager.customers.index', 'icon' => 'fas fa-search'],
                ['name' => 'Team Management', 'route' => 'woorolemanager.team.index', 'icon' => 'fas fa-users-cog'],
            ],
            'accountant_agent' => [
                ['name' => 'Dashboard', 'route' => 'woorolemanager.dashboard', 'icon' => 'fas fa-tachometer-alt'],
                ['name' => 'Financial Reports', 'route' => 'woorolemanager.financial.index', 'icon' => 'fas fa-chart-line'],
                ['name' => 'Transactions', 'route' => 'woorolemanager.transactions.index', 'icon' => 'fas fa-exchange-alt'],
            ],
            'accountant_manager' => [
                ['name' => 'Dashboard', 'route' => 'woorolemanager.dashboard', 'icon' => 'fas fa-tachometer-alt'],
                ['name' => 'Financial Reports', 'route' => 'woorolemanager.financial.index', 'icon' => 'fas fa-chart-line'],
                ['name' => 'Transactions', 'route' => 'woorolemanager.transactions.index', 'icon' => 'fas fa-exchange-alt'],
                ['name' => 'Team Management', 'route' => 'woorolemanager.team.index', 'icon' => 'fas fa-users-cog'],
            ],
            'warehouse_manager' => [
                ['name' => 'Dashboard', 'route' => 'woorolemanager.dashboard', 'icon' => 'fas fa-tachometer-alt'],
                ['name' => 'Inventory Overview', 'route' => 'woorolemanager.inventory.index', 'icon' => 'fas fa-boxes'],
                ['name' => 'Update Stock', 'route' => 'woorolemanager.stock.index', 'icon' => 'fas fa-warehouse'],
                ['name' => 'Team Management', 'route' => 'woorolemanager.team.index', 'icon' => 'fas fa-users-cog'],
            ],
            'warehouse_agent' => [
                ['name' => 'Dashboard', 'route' => 'woorolemanager.dashboard', 'icon' => 'fas fa-tachometer-alt'],
                ['name' => 'Inventory Overview', 'route' => 'woorolemanager.inventory.index', 'icon' => 'fas fa-boxes'],
                ['name' => 'Update Stock', 'route' => 'woorolemanager.stock.index', 'icon' => 'fas fa-warehouse'],
            ],
        ];

        return $navigation[$role->name] ?? [];
    }

} 