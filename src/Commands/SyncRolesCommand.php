<?php

namespace BoukjijTarik\WooRoleManager\Commands;

use Illuminate\Console\Command;
use BoukjijTarik\WooRoleManager\Models\Role;

class SyncRolesCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'roles:sync';

    /**
     * The console command description.
     */
    protected $description = 'Sync default roles for WooRoleManager';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Syncing default roles...');

        $defaultRoles = [
            [
                'name' => 'admin',
                'display_name' => 'Admin',
                'description' => 'Full access to all features',
                'guard_name' => 'web'
            ],
            [
                'name' => 'customer_service_agent',
                'display_name' => 'Customer Service Agent',
                'description' => 'View and manage customer orders',
                'guard_name' => 'web'
            ],
            [
                'name' => 'customer_service_manager',
                'display_name' => 'Customer Service Manager',
                'description' => 'Oversee customer service agents',
                'guard_name' => 'web'
            ],
            [
                'name' => 'accountant_agent',
                'display_name' => 'Accountant Agent',
                'description' => 'View financial data',
                'guard_name' => 'web'
            ],
            [
                'name' => 'accountant_manager',
                'display_name' => 'Accountant Manager',
                'description' => 'Oversee accounting agents',
                'guard_name' => 'web'
            ],
            [
                'name' => 'warehouse_manager',
                'display_name' => 'Warehouse Manager',
                'description' => 'Oversee inventory operations',
                'guard_name' => 'web'
            ],
            [
                'name' => 'warehouse_agent',
                'display_name' => 'Warehouse Agent',
                'description' => 'Manage stock and view orders',
                'guard_name' => 'web'
            ]
        ];

        foreach ($defaultRoles as $roleData) {
            $role = Role::firstOrCreate(
                ['name' => $roleData['name']],
                $roleData
            );

            if ($role->wasRecentlyCreated) {
                $this->line("Created role: {$role->display_name}");
            } else {
                $this->line("Role already exists: {$role->display_name}");
            }
        }

        $this->info('Roles synced successfully!');
    }
} 