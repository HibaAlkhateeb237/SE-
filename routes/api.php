<?php

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

// Routes محمية بـ Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class,'logout']);
});

// -----------------
// Account Routes
// -----------------
Route::middleware('auth:sanctum')->group(function () {
    Route::get('accounts', [AccountController::class,'index']);          // عرض جميع الحسابات
    Route::post('accounts', [AccountController::class,'store']);         // إنشاء حساب جديد
    Route::get('accounts/{id}', [AccountController::class,'show']);      // عرض حساب محدد
    Route::post('accounts/{id}/add-child', [AccountController::class,'addChild']); // إضافة حساب فرعي
});

// -----------------
// Test Route
// -----------------
Route::get('hello', function() {
    return response()->json([
        'message' => 'Hello World'
    ]);
});
