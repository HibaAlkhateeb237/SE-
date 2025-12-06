<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompleteProfileRequest;
use App\Http\Requests\SendOtpRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Http\Responses\ApiResponse;
use App\Http\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class AuthController extends Controller
{

    protected $auth;

    public function __construct(AuthService $authService)
    {
        $this->auth = $authService;

    }


    // تسجيل مستخدم جديد
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email'=>'required|string|email|unique:users,email',
            'password'=>'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
        ]);

        // توليد token جديد
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'=>'User registered successfully',
            'access_token'=>$token,
            'token_type'=>'Bearer',
            'user'=>$user
        ]);
    }

    // تسجيل دخول مستخدم موجود
    public function login(Request $request)
    {
        $request->validate([
            'email'=>'required|string|email',
            'password'=>'required|string'
        ]);

        $user = User::where('email',$request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.']
            ]);
        }

        // إنشاء token جديد لكل login
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'=>'Login successful',
            'access_token'=>$token,
            'token_type'=>'Bearer',
            'user'=>$user
        ]);
    }

    // تسجيل خروج المستخدم (حذف الـ token الحالي)
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message'=>'Logged out successfully'
        ]);
    }



    public function sendOtp(SendOtpRequest $request)
    {
        $result = $this->auth->sendOtp($request->email);

        if (isset($result['errors'])) {
            return ApiResponse::error('Failed to send OTP', $result['errors'], 422);
        }

        return ApiResponse::success('OTP sent successfully', $result);
    }





    public function verifyOtp(VerifyOtpRequest $request)
    {
        $result = $this->auth->verifyOtp(
            $request->email,
            $request->otp
        );

        if (isset($result['errors'])) {
            return ApiResponse::error('OTP verification failed', $result['errors'], 422);
        }

        return ApiResponse::success('User verified successfully', $result);
    }




    public function completeProfile(CompleteProfileRequest $request)
    {
        $this->auth->completeProfile($request->validated());
        return ApiResponse::success('Profile updated successfully.');
    }




}
