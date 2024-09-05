<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function update(Request $request): JsonResponse {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $request->user()->id,
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = $request->user();
        $user->email = $request->input('email');
        $user->name = $request->input('name');
        $user->phone_number = $request->input('phone'); // Adjust the field name if different

        // Save the updated user
        $user->save();

        return response()->json([
            'message' => 'User updated successfully',
            'user' => new UserResource($user),
        ]);
    }
}
