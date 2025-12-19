<?php

namespace App\Http\Services;

use App\Http\Repositories\UserRepository;
use App\Mail\SendOtpMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthService
{
    protected $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }





    public function sendOtp(string $email)
    {
        $errors = [];

        $user = $this->users->findByEmail($email);

        if ($user && $user->is_verified) {
            $errors['email'] = 'Email is already registered';
            return ['errors' => $errors];
        }


        if (!$user) {
            $user = $this->users->create([
                'email' => $email,
                'is_verified' => false
            ]);
        }

        $otp = rand(100000, 999999);

        $this->users->update($user, [
            'otp_code'       => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(10)
        ]);

        Mail::to($user->email)->send(new SendOtpMail($otp));
        return ['email' => $email];
    }



    public function verifyOtp(string $email, string $otp)
    {
        $errors = [];

        $user = $this->users->findByEmail($email);

        if (!$user) {
            $errors['email'] = 'User not found';
            return ['errors' => $errors];
        }

        if ($user->otp_code != $otp) {
            $errors['otp'] = 'Invalid OTP';
        }

        if (now()->greaterThan($user->otp_expires_at)) {
            $errors['otp'] = 'OTP expired';
        }

        if (!empty($errors)) {
            return ['errors' => $errors];
        }


        $this->users->update($user, [
            'is_verified'    => true,
            'otp_code'       => null,
            'otp_expires_at' => null,
        ]);


        if (!$user->hasRole('Customer')) {
            $user->assignRole('Customer');
        }


        $token = $user->createToken('auth_token')->plainTextToken;


        return compact( 'token');

    }




    public function completeProfile(array $data)
    {
        $user = $this->users->findByEmail($data['email']);

        $this->users->update($user, [
            'name'     => $data['name'],
            'password' => Hash::make($data['password']),
        ]);




        return true;
    }









}
