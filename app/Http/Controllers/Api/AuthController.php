<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HistoryCheck;
use App\Models\User;
use App\Models\UserApps;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);
        $user = UserApps::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Login failed, please check your username and password'
            ], 422);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'message' => 'Login successful'
        ]);
    }

    function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout successful'
        ]);
    }

    function register(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:user_apps,username',
            'password' => 'required|min:8',
            'email' => 'required|email|unique:user_apps,email',
            'name' => 'required',

        ]);

        $user = UserApps::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'email' => $request->email,
            'name' => $request->name,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'message' => 'Register successful'
        ]);
    }

    function auth()
    {
        $user = UserApps::find(Auth::user()->id);
        $dataSalah = HistoryCheck::where('type', 'tidak terdeteksi')->count();
        $data = HistoryCheck::count();
        $persentase = ($data - $dataSalah) / $data * 100;

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'persentase' => $persentase
        ]);
    }
}
