<?php

namespace BoukjijTarik\WooRoleManager\Commands;

use Illuminate\Console\Command;
use BoukjijTarik\WooRoleManager\Models\Permission;
use BoukjijTarik\WooRoleManager\Models\Role;

class SyncPermissionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'permissions:sync';

    /**
     * The console command description.
     */
    protected $description = 'Sync default permissions and assign them to roles';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Syncing default permissions...');

        $defaultPermissions = [
            // Admin permissions
            ['name' => 'manage-users', 'display_name' => 'Manage Users', 'description' => 'Create, edit, and delete users'],
            ['name' => 'manage-roles', 'display_name' => 'Manage Roles', 'description' => 'Create, edit, and delete roles'],
            ['name' => 'manage-permissions', 'display_name' => 'Manage Permissions', 'description' => 'Create, edit, and delete permissions'],
            ['name' => 'view-reports', 'display_name' => 'View Reports', 'description' => 'Access to all WooCommerce reports'],
            
            // Customer Service permissions
            ['name' => 'view-orders', 'display_name' => 'View Orders', 'description' => 'View customer orders'],
            ['name' => 'manage-orders', 'display_name' => 'Manage Orders', 'description' => 'Update order status and details'],
            ['name' => 'view-customers', 'display_name' => 'View Customers', 'description' => 'View customer information'],
            ['name' => 'manage-customers', 'display_name' => 'Manage Customers', 'description' => 'Update customer information'],
            
            // Financial permissions
            ['name' => 'view-financial-data', 'display_name' => 'View Financial Data', 'description' => 'View financial reports and data'],
            ['name' => 'manage-financial-data', 'display_name' => 'Manage Financial Data', 'description' => 'Update financial information'],
            ['name' => 'view-transactions', 'display_name' => 'View Transactions', 'description' => 'View transaction history'],
            
            // Warehouse permissions
            ['name' => 'view-inventory', 'display_name' => 'View Inventory', 'description' => 'View inventory levels'],
            ['name' => 'manage-inventory', 'display_name' => 'Manage Inventory', 'description' => 'Update inventory levels'],
            ['name' => 'view-stock', 'display_name' => 'View Stock', 'description' => 'View stock information'],
            ['name' => 'manage-stock', 'display_name' => 'Manage Stock', 'description' => 'Update stock levels'],
            
            // Team management permissions
            ['name' => 'manage-team', 'display_name' => 'Manage Team', 'description' => 'Manage team members'],
        ];

        // Create permissions
        foreach ($defaultPermissions as $permissionData) {
            $permission = Permission::firstOrCreate(
                ['name' => $permissionData['name']],
                array_merge($permissionData, ['guard_name' => 'web'])
            );

            if ($permission->wasRecentlyCreated) {
                $this->line("Created permission: {$permission->display_name}");
            } else {
                $this->line("Permission already exists: {$permission->display_name}");
            }
        }

        // Assign permissions to roles
        $this->assignPermissionsToRoles();

        $this->info('Permissions synced successfully!');
    }

    /**
     * Assign permissions to roles based on role hierarchy.
     */
    protected function assignPermissionsToRoles()
    {
        $this->info('Assigning permissions to roles...');

        // Admin gets all permissions
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $allPermissions = Permission::all();
            $adminRole->permissions()->sync($allPermissions->pluck('id'));
            $this->line("Assigned all permissions to Admin role");
        }

        // Customer Service Agent permissions
        $csAgentRole = Role::where('name', 'customer_service_agent')->first();
        if ($csAgentRole) {
            $csPermissions = Permission::whereIn('name', [
                'view-orders', 'manage-orders', 'view-customers'
            ])->get();
            $csAgentRole->permissions()->sync($csPermissions->pluck('id'));
            $this->line("Assigned CS Agent permissions");
        }

        // Customer Service Manager permissions
        $csManagerRole = Role::where('name', 'customer_service_manager')->first();
        if ($csManagerRole) {
            $csManagerPermissions = Permission::whereIn('name', [
                'view-orders', 'manage-orders', 'view-customers', 'manage-customers', 'manage-team'
            ])->get();
            $csManagerRole->permissions()->sync($csManagerPermissions->pluck('id'));
            $this->line("Assigned CS Manager permissions");
        }

        // Accountant Agent permissions
        $accAgentRole = Role::where('name', 'accountant_agent')->first();
        if ($accAgentRole) {
            $accPermissions = Permission::whereIn('name', [
                'view-financial-data', 'view-transactions'
            ])->get();
            $accAgentRole->permissions()->sync($accPermissions->pluck('id'));
            $this->line("Assigned Accountant Agent permissions");
        }

        // Accountant Manager permissions
        $accManagerRole = Role::where('name', 'accountant_manager')->first();
        if ($accManagerRole) {
            $accManagerPermissions = Permission::whereIn('name', [
                'view-financial-data', 'manage-financial-data', 'view-transactions', 'manage-team'
            ])->get();
            $accManagerRole->permissions()->sync($accManagerPermissions->pluck('id'));
            $this->line("Assigned Accountant Manager permissions");
        }

        // Warehouse Agent permissions
        $whAgentRole = Role::where('name', 'warehouse_agent')->first();
        if ($whAgentRole) {
            $whPermissions = Permission::whereIn('name', [
                'view-inventory', 'view-stock', 'manage-stock'
            ])->get();
            $whAgentRole->permissions()->sync($whPermissions->pluck('id'));
            $this->line("Assigned Warehouse Agent permissions");
        }

        // Warehouse Manager permissions
        $whManagerRole = Role::where('name', 'warehouse_manager')->first();
        if ($whManagerRole) {
            $whManagerPermissions = Permission::whereIn('name', [
                'view-inventory', 'manage-inventory', 'view-stock', 'manage-stock', 'manage-team'
            ])->get();
            $whManagerRole->permissions()->sync($whManagerPermissions->pluck('id'));
            $this->line("Assigned Warehouse Manager permissions");
        }
    }
} 