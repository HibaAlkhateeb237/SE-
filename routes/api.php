<?php


use App\Http\Controllers\AccountFeatureController;


use App\Http\Controllers\TransactionApprovalController;
use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RoleController;
use App\Http\Controllers\AdminDashboardController;

use App\Http\Controllers\SupportReportsController;
use App\Http\Controllers\UserController;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountController;




// -----------------
// Public Auth Routes
// -----------------

Route::post('register', [AuthController::class,'register']);
Route::post('login', [AuthController::class,'login']);

Route::post('sendOtp',[AuthController::class, 'sendOtp']);
Route::post('verify-otp', [AuthController::class, 'verifyOtp']);

Route::post('/accounts/{id}/change-state', [AccountController::class, 'changeState']);


// -----------------
// Customer Protected Routes
// -----------------

//Route::middleware('auth:sanctum')->group(function () {
//    Route::post('complete-profile', [AuthController::class, 'completeProfile']);
//
//});

Route::middleware(['auth:sanctum','role:Customer'])->group(function () {

    Route::post('logout', [AuthController::class,'logout']);
    Route::post('complete-profile', [AuthController::class, 'completeProfile']);

    // Accounts
    Route::get('accounts', [AccountController::class,'index']);
    Route::get('accounts/{id}/feature', [AccountFeatureController::class,'show']);
    Route::post('accounts', [AccountController::class,'store']);
    Route::get('accounts/{id}', [AccountController::class,'show']);
    Route::post('accounts/{id}/add-child', [AccountController::class,'addChild']);

    Route::get('/account-types', [AccountController::class, 'indexType']);

    Route::prefix('transactions')->group(function () {
        Route::post('deposit', [TransactionController::class, 'deposit']);
        Route::post('withdraw', [TransactionController::class, 'withdraw']);
        Route::post('transfer', [TransactionController::class, 'transfer']);
        Route::get('index', [TransactionController::class, 'index']);

        Route::get('my-transactions', [TransactionApprovalController::class, 'myTransactions']);

        Route::post('{id}/approve', [TransactionApprovalController::class, 'approve']);
        Route::post('{id}/reject', [TransactionApprovalController::class, 'reject']);




    });

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
    Route::get('/dashboard', [AdminDashboardController::class, 'stats'])
        ->middleware('permission:system dashboard');


    // ================= Reports =================
    Route::get('/reports/daily-transactions', [SupportReportsController::class, 'dailyTransactions']) ->middleware('permission:view audit logs');
    Route::get('/reports/account-summary', [SupportReportsController::class, 'accountSummary'])   ->middleware('permission:view audit logs');
    Route::get('/reports/audit-logs', [SupportReportsController::class, 'auditLogs']) ->middleware('permission:view audit logs');

});
