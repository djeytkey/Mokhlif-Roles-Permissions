<?php

use Illuminate\Support\Facades\Route;
use BoukjijTarik\WooRoleManager\Http\Controllers\DashboardController;
use BoukjijTarik\WooRoleManager\Http\Controllers\UserController;
use BoukjijTarik\WooRoleManager\Http\Controllers\RoleController;
use BoukjijTarik\WooRoleManager\Http\Controllers\PermissionController;

// Dashboard routes
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Admin routes - only accessible by admin
Route::middleware(['check.role:admin'])->group(function () {
    // User management
    Route::resource('users', UserController::class);
    
    // Role management
    Route::resource('roles', RoleController::class);
    
    // Permission management
    Route::resource('permissions', PermissionController::class);
    
    // WooCommerce reports
    Route::get('reports', [DashboardController::class, 'reports'])->name('reports');
});

// Customer Service routes
Route::middleware(['check.role:admin,customer_service_agent,customer_service_manager'])->group(function () {
    Route::get('orders', [DashboardController::class, 'orders'])->name('orders.index');
    Route::get('customers', [DashboardController::class, 'customers'])->name('customers.index');
});

// Customer Service Manager routes
Route::middleware(['check.role:admin,customer_service_manager'])->group(function () {
    Route::get('team', [DashboardController::class, 'team'])->name('team.index');
});

// Accountant routes
Route::middleware(['check.role:admin,accountant_agent,accountant_manager'])->group(function () {
    Route::get('financial', [DashboardController::class, 'financial'])->name('financial.index');
    Route::get('transactions', [DashboardController::class, 'transactions'])->name('transactions.index');
});

// Accountant Manager routes
Route::middleware(['check.role:admin,accountant_manager'])->group(function () {
    Route::get('accounting-team', [DashboardController::class, 'accountingTeam'])->name('accounting-team.index');
});

// Warehouse routes
Route::middleware(['check.role:admin,warehouse_manager,warehouse_agent'])->group(function () {
    Route::get('inventory', [DashboardController::class, 'inventory'])->name('inventory.index');
    Route::get('stock', [DashboardController::class, 'stock'])->name('stock.index');
});

// Warehouse Manager routes
Route::middleware(['check.role:admin,warehouse_manager'])->group(function () {
    Route::get('warehouse-team', [DashboardController::class, 'warehouseTeam'])->name('warehouse-team.index');
}); 