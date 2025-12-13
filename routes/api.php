<?php

use App\Http\Controllers\RoleController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountController;

use Illuminate\Support\Facades\Route;



// -----------------
// Public Auth Routes
// -----------------

Route::post('register', [AuthController::class,'register']);
Route::post('login', [AuthController::class,'login']);

Route::post('sendOtp',[AuthController::class, 'sendOtp']);
Route::post('verify-otp', [AuthController::class, 'verifyOtp']);


// -----------------
// Customer Protected Routes
// -----------------

Route::middleware('auth:sanctum')->group(function () {
    Route::post('complete-profile', [AuthController::class, 'completeProfile']);
});

Route::middleware(['auth:sanctum','role:Customer'])->group(function () {

    Route::post('logout', [AuthController::class,'logout']);
//    Route::post('complete-profile', [AuthController::class, 'completeProfile']);

    // Accounts
    Route::get('accounts', [AccountController::class,'index']);
    Route::post('accounts', [AccountController::class,'store']);
    Route::get('accounts/{id}', [AccountController::class,'show']);
    Route::post('accounts/{id}/add-child', [AccountController::class,'addChild']);

    Route::get('/account-types', [AccountController::class, 'indexType']);
});



// -----------------
// Admin Protected Routes
// -----------------

Route::middleware(['auth:sanctum','role:Admin'])->group(function () {

    // ================= Roles & Permissions =================

    Route::get('/roles', [RoleController::class, 'index'])
        ->middleware('permission:manage roles');

    Route::get('/roles/{id}', [RoleController::class, 'show'])
        ->middleware('permission:manage roles');

    Route::post('/roles', [RoleController::class, 'store'])
        ->middleware('permission:manage roles');;

    Route::delete('/roles/{id}',[RoleController::class,'destroy'])
        ->middleware('permission:manage roles');

    Route::get('/permissions', [RoleController::class, 'indexPermission'])
        ->middleware('permission:manage permissions');

    Route::put('/roles/{id}', [RoleController::class, 'update'])
        ->middleware('permission:manage roles');

    Route::put('/roles/{id}/permissions', [RoleController::class, 'updatePermissions'])
        ->middleware('permission:manage roles');
    // ================= Users =================

    Route::get('/users', [UserController::class, 'index'])
        ->middleware('permission:manage users');

    Route::get('/users/{id}', [UserController::class, 'show'])
        ->middleware('permission:manage users');

    Route::post('/users', [UserController::class, 'store'])
        ->middleware('permission:manage users');

    Route::put('/users/{id}', [UserController::class, 'update'])
        ->middleware('permission:manage users');

    Route::delete('/users/{id}', [UserController::class, 'destroy'])
        ->middleware('permission:manage users');


    // Assign / Remove Roles
    Route::post('/users/{id}/assign-role', [UserController::class, 'assignRole'])
        ->middleware('permission:manage roles');

    Route::post('/users/{id}/remove-role', [UserController::class, 'removeRole'])
        ->middleware('permission:manage roles');




    // ================= Dashboard =================
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])
        ->middleware('permission:system dashboard');


    // ================= Reports =================
    Route::get('/reports/daily-transactions', [ReportsController::class, 'dailyTransactions'])
        ->middleware('permission:view audit logs');

    Route::get('/reports/account-summaries', [ReportsController::class, 'accountSummaries'])
        ->middleware('permission:view audit logs');

    Route::get('/reports/audit-logs', [ReportsController::class, 'auditLogs'])
        ->middleware('permission:view audit logs');
});
