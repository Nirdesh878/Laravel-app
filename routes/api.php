<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\GoogleLoginController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Models\User;  // Add this line
use Illuminate\Http\Request;
use Illuminate\Support\Str;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
}); 

Route::middleware('auth:sanctum')->post('/update-location', [LocationController::class, 'updateLocation']);
Route::get('/get-locations', [LocationController::class, 'getLocations']);
Route::get('/get-nearby-washers', [LocationController::class, 'getNearbyWashers']);

Route::post('/google-login', [GoogleLoginController::class, 'login']);

// Route::post('/google-login', function (Request $request) {
//     // Validate required fields
//     $validated = $request->validate([
//         'email' => 'required|email',
//         'name' => 'required|string',
//         'google_id' => 'required|string'
//     ]);

//     try {
//         // Find or create user
//         $user = User::firstOrCreate(
//             ['email' => $request->email],
//             [
//                 'name' => $request->name,
//                 'password' => Hash::make(Str::random(24)), // Random password for Google users
//                 'google_id' => $request->google_id,
//                 'profile_picture' => $request->profile_picture ?? null
//             ]
//         );

//         // Update Google-specific fields if user already existed
//         if (!$user->wasRecentlyCreated) {
//             $user->update([
//                 'google_id' => $request->google_id,
//                 'profile_picture' => $request->profile_picture ?? $user->profile_picture
//             ]);
//         }

//         // Create token
//         $token = $user->createToken('authToken')->plainTextToken;

//         return response()->json([
//             'user' => $user,
//             'token' => $token
//         ]);

//     } catch (\Exception $e) {
//         \Log::error('Google login error: '.$e->getMessage());
//         return response()->json([
//             'message' => 'Login failed',
//             'error' => $e->getMessage()
//         ], 500);
//     }
// });

Route::get('/test', function () {
    return response()->json(['success' => true]);
});




