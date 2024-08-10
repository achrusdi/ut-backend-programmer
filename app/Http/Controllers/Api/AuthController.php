<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'min:3', 'max:16'],
            'email' => ['required', 'string', 'email', 'max:64', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'User not created',
                'error' => $validator->errors(),
            ]);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return response()->json(
            [
                'status' => 'success',
                'code' => 201,
                'message' => 'User created successfully',
                'data' => $user,
            ],
            201,
        );
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:64'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'code' => 400,
                'message' => 'User not logged in',
                'error' => $validator->errors(),
            ], 400);
        }

        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'code' => 401,
                'message' => 'email or password is incorrect',
            ], 401);
        }

        // // $token = $user->createToken('auth_token')->plainTextToken;
        $token = Auth::attempt(['email' => $user->email, 'password' => $request->password]);
        $user['type'] = 'Bearer';
        $user['token'] = $token;
        $user['expires_in'] = Auth::factory()->getTTL() * 60;
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'User logged in successfully',
            'data' => $user,
        ], 200);
    }

    public function logout()
    {
        auth()->logout();
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'User logged out successfully',
        ], 200);
    }
}
