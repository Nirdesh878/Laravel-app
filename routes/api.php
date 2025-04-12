<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LocationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
}); 

Route::middleware('auth:sanctum')->post('/update-location', [LocationController::class, 'updateLocation']);
Route::get('/get-locations', [LocationController::class, 'getLocations']);
Route::get('/get-nearby-washers', [LocationController::class, 'getNearbyWashers']);

Route::post('/google-login', function (Request $request) {
    $user = User::where('email', $request->email)->first();

    if (!$user) {
        // Create new user for Google login
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make(Str::random(24)), // Random password for Google users
            'google_id' => $request->google_id,
            'profile_picture' => $request->profile_picture
        ]);
    }

    $token = $user->createToken('authToken')->plainTextToken; 
    
    return response()->json([
        'user' => $user,
        'token' => $token
    ]);
});

Route::get('/test', function () {
    return response()->json(['success' => true]);
});




