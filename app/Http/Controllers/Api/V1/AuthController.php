<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'phone_number' => 'nullable|string|max:10', // Optional
                'role' => 'required|in:admin,user',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'error' => $validator->errors(),
                ], 422);
            }

            // Create the user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone_number' => $request->phone_number,
                'role' => $request->role,
            ]);

            // Generate Sanctum token
            $token = $user->createToken($request->email)->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'User registered successfully',
                'data' => [
                    'user' => new UserResource($user),
                    'token' => $token,
                ],
            ], 201);
        } catch (\Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to register user',
                'error' => $ex->getMessage(),
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'error' => $validator->errors(),
                ], 422);
            }

            // Attempt to authenticate
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid credentials',
                    'error' => 'The provided credentials are incorrect.',
                ], 401);
            }

            // Generate Sanctum token
            $token = $user->createToken($request->email)->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'User logged in successfully',
                'data' => [
                    'user' => new UserResource($user),
                    'token' => $token,
                ],
            ], 200);
        } catch (\Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to log in',
                'error' => $ex->getMessage(),
            ], 500);
        }
    }

    public function currentUser(Request $request)
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'User retrieved successfully',
                'data' => new UserResource($request->user()),
            ], 200);
        } catch (\Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve user',
                'error' => $ex->getMessage(),
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return response()->json([
                'success' => true,
                'message' => 'User logged out successfully',
            ], 200);
        } catch (\Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to log out',
                'error' => $ex->getMessage(),
            ], 500);
        }
    }
}
