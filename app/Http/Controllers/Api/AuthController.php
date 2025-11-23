<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        // 1. Validasi Input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users', // Harus unik
            'password' => 'required|string|min:8|confirmed', // 'confirmed' butuh 'password_confirmation'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // 2. Buat User Baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Password wajib di-hash
        ]);

        // 3. Buat Token Baru (Auto-login)
        $token = $user->createToken('auth_token')->plainTextToken;

        // 4. Kembalikan Respon Sukses
        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil! Anda telah login.',
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
            ],
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201); // Status 201 Created
    }

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

    public function logout(Request $request)
    {
        // 1. Ambil user yang sedang terautentikasi (berdasarkan Bearer Token)
        // Jika request sampai di sini, artinya token sudah diverifikasi oleh middleware
        $user = $request->user();

        // 2. Hapus token saat ini (currentAccessToken)
        // Ini akan menghapus baris token dari tabel personal_access_tokens
        $user->currentAccessToken()->delete();

        // 3. Kembalikan Respon Sukses
        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil. Token telah dihapus.'
        ], 200);
    }
}
