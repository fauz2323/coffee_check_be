<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    function getProfile()
    {
        $user = User::find(auth()->user()->id);

        return response()->json([
            'user' => $user
        ]);
    }

    function changePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8',
            'new_password' => 'required|min:8',
        ]);

        $user = User::find(auth()->user()->id);

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Password is incorrect'
            ], 422);
        }


        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'message' => 'Change password successful'
        ]);
    }

    function changeProfile(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . auth()->user()->id,
        ]);

        $user = User::find(auth()->user()->id);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return response()->json([
            'message' => 'Change profile successful'
        ]);
    }
}
