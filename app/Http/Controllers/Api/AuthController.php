<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validasi Input Email dan Password
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422); // Status 422 Unprocessable Entity
        }

        // 2. Coba Otentikasi Kredensial
        // Auth::attempt akan membandingkan password mentah dengan hash di database
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau Password tidak valid'
            ], 401); // Status 401 Unauthorized
        }

        // 3. Ambil User yang berhasil login
        // Ambil User yang berhasil login untuk membuat token
        $user = User::where('email', $request->email)->firstOrFail();

        // 4. Buat Token Baru
        // Sanctum membuat token dan menyimpannya di tabel personal_access_tokens
        $token = $user->createToken('auth_token')->plainTextToken;

        // 5. Kembalikan Respon Sukses
        return response()->json([
            'success' => true,
            'message' => 'Login berhasil!',
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
            ],
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 200);
    }
}
