<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Traits\ApiResponse;
use App\Traits\JwtHandler;
use App\Traits\ImageUpload;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    use ApiResponse, JwtHandler, ImageUpload;
    /**
     * Register a new user.
     */
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'profile_image' => 'sometimes|image'
        ]);

        $imagePath = null;
        if ($request->hasFile('profile_image')) {
            $imagePath = $this->uploadImage($request->file('profile_image'), 'profile_images');
        }

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
            'profile_image' => $imagePath,
        ]);


        return $this->successResponse(compact('user'), 'User registered successfully', 201);
    }


    /**
     * Authenticate a user and return a JWT.
     */
    public function login(Request $request)
    {
        $credentials = $request->only('user_id', 'email', 'password');
        $company_code = $request->input('company_code');

        $user = User::where(function ($query) use ($credentials) {
            $query->where('email', $credentials['email'] ?? null)
                ->orWhere('user_id', $credentials['user_id'] ?? null);
        })->whereHas('company', function ($query) use ($company_code) {
            $query->where('company_code', $company_code);
        })->first();

        if (!$user) {
            return $this->errorResponse('Invalid credentials or company code', 401);
        }

        $loginCredentials = [
            'email' => $user->email,
            'password' => $credentials['password']
        ];

        try {
            if (!$token = JWTAuth::attempt($loginCredentials)) {
                return $this->errorResponse('Invalid credentials', 401);
            }
        } catch (JWTException $e) {
            return $this->handleJwtException($e);
        }

        $permission = $user->roles->pluck('permissions')->flatten()->pluck('name')->unique();
        return $this->successResponseUser(compact('token', 'user', 'permission'), 'User logged in successfully');
    }
    public function sendOtp(Request $request)
    {
        $user = Auth::user();

        $lastSent = $user->otp_last_sent_at;
        $currentTime = now();
        $timeout = 60; // 60 seconds timeout

        // if ($lastSent && $currentTime->diffInSeconds($lastSent) < $timeout) {
        //     $remainingTime = $timeout - $currentTime->diffInSeconds($lastSent);
        //     return response()->json(['message' => 'OTP already sent. Please wait ' . $remainingTime . ' seconds before requesting a new OTP.'], 429);
        // }

        $otp = rand(1000, 9999);
        $user->otp = $otp;
        $user->otp_expiry = $currentTime->addMinutes(10);
        $user->otp_last_sent_at = $currentTime;
        $user->save();

        Mail::to($user->email)->send(new \App\Mail\SendOtp($otp));

        return response()->json(['message' => 'OTP sent successfully']);
    }

    public function verifyOtp(Request $request)
    {
        $user = Auth::user();
        $otp = $request->input('otp');

        // if ($user->otp !== $otp || $user->otp_expiry->isPast()) {
        //     return response()->json(['error' => 'Invalid or expired OTP'], 401);
        // }

        $user->otp = null;
        $user->otp_expiry = null;
        $user->save();

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('token'));
    }
    /**
     * Log the user out (Invalidate the token).
     */
    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return $this->successResponse([], 'User successfully logged out');
        } catch (JWTException $e) {
            return $this->handleJwtException($e);
        }
    }

    /**
     * Get the authenticated user.
     */
    public function me()
    {
        $user = auth()->user();

        $data = [
            'user' => $user,
            'permissions' => $user->roles->pluck('permissions')->flatten()->pluck('name')->unique(),
        ];

        return $this->successResponse($data, 'User details retrieved successfully');
    }
}