<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Google\Client as GoogleClient;

class GoogleLoginController extends Controller
{
    public function login(Request $request)
    {
        // print_r('hi');
        // die;
        // Validate request
        $validated = $request->validate([
            'email' => 'required|email',
            'google_id' => 'required|string',
            'id_token' => 'required|string',
            'name' => 'sometimes|string'
        ]);
        // print_r('hi');
        // die;

        try {
            // Verify Google ID token
            $client = new GoogleClient(['client_id' => env('GOOGLE_CLIENT_ID')]);
            $payload = $client->verifyIdToken($validated['id_token']);
        //     print_r('hi');
        // die;
        // print_r($payload);
        // die;
            
            if (!$payload) {
                return response()->json(['message' => 'Invalid Google token'], 401);
            }

            // print_r($validated['email']);
            // die;
            // Verify token matches request
            if ($payload['email'] !== $validated['email'] ) {
                return response()->json(['message' => 'Token mismatch'], 401);
            }
        //     print_r('hi');
        // die;

            // Find or create user
            $user = User::updateOrCreate(
                ['email' => $validated['email']],
                [
                    'name' => $validated['name'] ?? $payload['name'] ?? 'Google User',
                    'google_id' => $validated['google_id'],
                    'password' => Hash::make(Str::random(24)),
                    'email_verified_at' => now()
                ]
            );

            // Create API token
            $token = $user->createToken('google-auth')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token
            ]);

        } catch (\Exception $e) {
            \Log::error('Google login error: ' . $e->getMessage());
            return response()->json(['message' => 'Authentication failed'], 500);
        }
    }
}