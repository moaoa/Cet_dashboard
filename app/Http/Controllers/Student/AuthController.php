<?php

namespace App\Http\Controllers\Student;

use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    // jegister a new user
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $base = 181130;

        $numberOfUsers = User::count();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,  // No need to hash the password here
            'ref_number' => $base + $numberOfUsers,
            'type' => UserType::Student->value
        ]);
        $token = $user->createToken('auth_token')->plainTextToken;

        //Auth::login($user);

        return response()->json(['message' => 'User registered successfully', 'token' => $token, 'user' => new UserResource($user)], 201);
    }

    // User login
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = User::where('email', $request->input('email'))->first();
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json(['message' => 'Login successful', 'user' => new UserResource($user), 'token' => $token]);
        } else {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }

    // User logout
    public function logout(Request $request): JsonResponse
    {
        Auth::logout();

        return response()->json(['message' => 'Logout successful']);
    }

    // Get the authenticated user
    public function user(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }
}
