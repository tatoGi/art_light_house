<?php

namespace App\Http\Controllers\Website\Auth;

use App\Http\Controllers\Controller;
use App\Models\WebUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;


class RegisterController extends Controller
{

    public function register(Request $request)
    {
        
        try {
            $request->validate([
                'first_name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:web_users',
                'password' => 'required|string|min:8|confirmed',
            ]);

            $user = WebUser::create([
                'first_name' => $request->first_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Authenticate the user with the 'webuser' guard for the current request
            Auth::guard('webuser')->login($user);
            
            // Create a new token for API authentication
            $token = $user->createToken(
                name: 'auth_token',
                expiresAt: now()->addDays(30) // Token expires in 30 days
            )->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'User registered successfully',
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
                'redirect' => '/'
            ], 201);


        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
