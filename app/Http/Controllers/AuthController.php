<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        print_r('hi');
        die;
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6'
        ]);
        // print_r('hi');
        // die;
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'User registered successfully']);
    }

    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Log;
    
    public function login(Request $request)
    {
        print_r('hi');
        die;
        try {
            Log::info('Login attempt: ' . $request->email);
           print_r('Login attempt: ' . $request->email);
    
            $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);
    
            $user = User::where('email', $request->email)->first();
    
            if (!$user) {
                Log::warning('User not found: ' . $request->email);
                return response()->json(['error' => 'User not found'], 401);
            }
    
            if (!Hash::check($request->password, $user->password)) {
                Log::warning('Invalid password for user: ' . $request->email);
                return response()->json(['error' => 'Invalid credentials'], 401);
            }
    
            $token = $user->createToken('auth_token')->plainTextToken;
            Log::info('Login success for: ' . $request->email);
    
            return response()->json(['token' => $token]);
        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            return response()->json(['error' => 'Server Error'], 500);
        }
    }
    

    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function logout(Request $request)
    {
        // Revoke the current user's token
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
}
