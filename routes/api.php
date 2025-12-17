<?php

use App\Http\Controllers\TransactionApprovalController;
use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| هذه الـ routes جاهزة للاستخدام مع الـ backend Laravel.
|
*/

// -----------------
// Auth Routes
// -----------------
Route::post('register', [AuthController::class,'register']);
Route::post('login', [AuthController::class,'login']);

Route::post('sendOtp',[AuthController::class, 'sendOtp']);
Route::post('verify-otp', [AuthController::class, 'verifyOtp']);

Route::post('/accounts/{id}/change-state', [AccountController::class, 'changeState']);


// Routes محمية بـ Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class,'logout']);
    Route::post('complete-profile', [AuthController::class, 'completeProfile']);

});

// -----------------
// Account Routes
// -----------------
Route::middleware('auth:sanctum')->group(function () {
    Route::get('accounts', [AccountController::class,'index']);          // عرض جميع الحسابات
    Route::post('accounts', [AccountController::class,'store']);         // إنشاء حساب جديد
    Route::get('accounts/{id}', [AccountController::class,'show']);      // عرض حساب محدد
    Route::post('accounts/{id}/add-child', [AccountController::class,'addChild']); // إضافة حساب فرعي
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
// Test Route
// -----------------
Route::get('hello', function() {
    return response()->json([
        'message' => 'Hello World'
    ]);
});
