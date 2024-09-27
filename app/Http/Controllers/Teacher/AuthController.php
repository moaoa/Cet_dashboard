<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Teacher\TeacherResource;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    // jegister a new user
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $base = 181130;

        $numberOfTeachers = Teacher::count();

        $user = Teacher::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,  // No need to hash the password here
            'ref_number' => $base + $numberOfTeachers,
        ]);
        $token = $user->createToken('teacher_token')->plainTextToken;

        //Auth::login($user);

        return response()->json(['message' => 'Teacher registered successfully', 'token' => $token, 'user' => new TeacherResource($user)], 201);
    }

    // Teacher login
    public function login(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'oneSignalId' => 'nullable|string',
        ]);


        $teacher = Teacher::where('email', $data['email'])->first();

        if (!$teacher || !Hash::check($data['password'], $teacher->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        if ($request->oneSignalId) {
            $deviceSubscriptions = json_decode($teacher->device_subscriptions, true);
            $deviceSubscriptions[] = $request->oneSignalId;
            $teacher->device_subscriptions = json_encode($deviceSubscriptions);

            $teacher->save();
        }

        $token = $teacher->createToken('auth_token')->plainTextToken;
        return response()->json(['message' => 'Login successful', 'user' => new TeacherResource($teacher), 'token' => $token]);
    }

    // Teacher logout
    public function logout(Request $request): JsonResponse
    {
        Auth::logout();

        return response()->json(['message' => 'Logout successful']);
    }
}
