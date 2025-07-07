<?php

namespace App\Http\Controllers\api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validasi manual agar bisa kontrol response
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:users,name',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'User sudah terdaftar',
                'errors' => $validator->errors()
            ], 422); // Unprocessable Entity
        }

        // Simpan user baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id' => $request->role_id ?? 2, 
        ]);

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Register berhasil | Silakan login untuk mendapatkan token',
            'data' => [
                'user' => $user,
                'token' => $token
            ],
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Login gagal. Email atau password salah.',
            ], 401);
        }

        // Ambil role user, misal dari kolom role atau relasi role->name
        $roleName = $user->role ? $user->role->name : 'user'; // default ke 'user' jika role null

        // Buat abilities array dari role (bisa 1 role atau banyak)
        $abilities = [$roleName];

        // Generate token dengan abilities sesuai role
        $token = $user->createToken('api_token', $abilities)->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'data' => [
                'user' => $user,
                'role' => $roleName,
                'token' => $token,
            ],
        ]);
    }
}
