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
        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return $this->errorResponse('Invalid credentials', 401);
            }
        } catch (JWTException $e) {
            return $this->handleJwtException($e);
        }
        $user = User::where('email', $request->email)->first();

        $permission = $user->roles->pluck('permissions')->flatten()->pluck('name')->unique();
        return $this->successResponseUser(compact('token', 'user', 'permission'), 'User logged in successfully');
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
