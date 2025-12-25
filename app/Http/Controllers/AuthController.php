<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;      // <-- questa riga serve
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        // Durata in secondi
        $duration = 3600;

        // Calcola scadenza (timestamp)
        $expiresAt = now()->addSeconds($duration)->timestamp;

        return response()->json([
            'token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => $expiresAt,   // timestamp di scadenza
            'duration' => $duration,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email
            ]
        ]);
    }

    // Logout e revoca token
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out']);
    }

}
