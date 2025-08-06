<?php

namespace BoukjijTarik\WooRoleManager\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use BoukjijTarik\WooRoleManager\WooRoleManager;

class DashboardController extends Controller
{
    protected $wooRoleManager;

    public function __construct(WooRoleManager $wooRoleManager)
    {
        $this->wooRoleManager = $wooRoleManager;
    }

    /**
     * Display the dashboard based on user's role.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $dashboardView = $this->wooRoleManager->getUserDashboardView($user);
        $navigation = $this->wooRoleManager->getSidebarNavigation($user);

        return view($dashboardView, compact('user', 'navigation'));
    }

    /**
     * Display WooCommerce reports (Admin only).
     */
    public function reports()
    {
        return view('woorolemanager::reports.index');
    }

    /**
     * Display orders (Customer Service).
     */
    public function orders()
    {
        return view('woorolemanager::orders.index');
    }

    /**
     * Display customers (Customer Service).
     */
    public function customers()
    {
        return view('woorolemanager::customers.index');
    }

    /**
     * Display team management (Managers).
     */
    public function team()
    {
        return view('woorolemanager::team.index');
    }

    /**
     * Display financial data (Accountants).
     */
    public function financial()
    {
        return view('woorolemanager::financial.index');
    }

    /**
     * Display transactions (Accountants).
     */
    public function transactions()
    {
        return view('woorolemanager::transactions.index');
    }

    /**
     * Display accounting team (Accountant Manager).
     */
    public function accountingTeam()
    {
        return view('woorolemanager::accounting-team.index');
    }

    /**
     * Display inventory (Warehouse).
     */
    public function inventory()
    {
        return view('woorolemanager::inventory.index');
    }

    /**
     * Display stock management (Warehouse).
     */
    public function stock()
    {
        return view('woorolemanager::stock.index');
    }

    /**
     * Display warehouse team (Warehouse Manager).
     */
    public function warehouseTeam()
    {
        return view('woorolemanager::warehouse-team.index');
    }
} 