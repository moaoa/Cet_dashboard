<?php

namespace App\Http\Controllers;

use App\Http\Resources\Teacher\TeacherResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    public function update(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:teachers',
            'phone' => 'required|string|max:255',
            // 'password' => 'required|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone;
        // $user->password = Hash::make($request->password);

        $user->save();

        return response()->json([
            'message' => 'تم تحديث المعلم بنجاح',
            'teacher' => new TeacherResource($user)
        ], 201);
    }
}
