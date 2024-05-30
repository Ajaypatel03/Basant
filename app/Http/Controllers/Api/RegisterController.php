<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        // Check if the user already exists
        $existingUser = User::where('email', $request->input('email'))->first();
        if ($existingUser) {
            return response()->json([
                'status' => false,
                'message' => 'User already exists. Please login.',
            ], 409);
        }

        // Create the new user
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        // Respond with a success message
        return response()->json(['message' => 'User created successfully!'], 201);
    }

    public function login(Request $request): JsonResponse
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }
        // Attempt to find the user by email
        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            // Create an access token for the user
            $token = $user->createToken("LoginToken")->accessToken;
                return response()->json([
                    'status' => true,
                    'message' => 'User login successful',
                    'token' => $token,
                    'data' => $user
                ], 200);
            } elseif ($user) {
                // If user exists but password does not match
                return response()->json([
                    'status' => false,
                    'message' => 'Password does not match',
                    'data' => []
                ], 401);
        } else {
                // If user does not exist
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid email address',
                    'data' => []
                ], 404);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->token()->revoke();

        return response()->json([
            'status' => true,
            'message' => 'User logged out successfully.'
        ]);
    }
}