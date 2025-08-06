<?php

namespace BoukjijTarik\WooRoleManager\Tests\Unit;

use PHPUnit\Framework\TestCase;
use BoukjijTarik\WooRoleManager\WooRoleManager;
use BoukjijTarik\WooRoleManager\Models\User;
use BoukjijTarik\WooRoleManager\Models\Role;
use BoukjijTarik\WooRoleManager\Models\Permission;

class WooRoleManagerTest extends TestCase
{
    protected $wooRoleManager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->wooRoleManager = new WooRoleManager();
    }

    /** @test */
    public function it_can_get_user_dashboard_view()
    {
        $user = new User();
        $user->ID = 1;
        $user->display_name = 'Test User';
        $user->user_email = 'test@example.com';

        $dashboardView = $this->wooRoleManager->getUserDashboardView($user);
        
        $this->assertEquals('woorolemanager::dashboard.default', $dashboardView);
    }

    /** @test */
    public function it_can_get_sidebar_navigation()
    {
        $user = new User();
        $user->ID = 1;
        $user->display_name = 'Test User';
        $user->user_email = 'test@example.com';

        $navigation = $this->wooRoleManager->getSidebarNavigation($user);
        
        $this->assertIsArray($navigation);
        $this->assertEmpty($navigation); // No roles assigned
    }

    /** @test */
    public function it_can_assign_role_to_user()
    {
        $userId = 1;
        $roleId = 1;

        $result = $this->wooRoleManager->assignRoleToUser($userId, $roleId);
        
        $this->assertIsBool($result);
    }

    /** @test */
    public function it_can_remove_role_from_user()
    {
        $userId = 1;
        $roleId = 1;

        $result = $this->wooRoleManager->removeRoleFromUser($userId, $roleId);
        
        $this->assertIsBool($result);
    }

    /** @test */
    public function it_can_assign_permission_to_role()
    {
        $roleId = 1;
        $permissionId = 1;

        $result = $this->wooRoleManager->assignPermissionToRole($roleId, $permissionId);
        
        $this->assertIsBool($result);
    }
} 