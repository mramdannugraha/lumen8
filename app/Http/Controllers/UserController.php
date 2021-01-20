<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request, [
                'email' => 'required|unique:users|email',
                'password' => 'required|min:6'
            ]
        );

        $email = $request->input('email');
        $password = Hash::make($request->input('password'));
        $user = User::create([
            'email' => $email,
            'password' => $password
        ]);

        return response()->json(['message' => 'user created',
            'data user' => $user],200);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
                'email' => 'required|email',
                'password' => 'required|min:6'
            ]
        );

        $email = $request->input('email');
        $password = $request->input('password');

        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json(['message' => 'FAILED! User Not found'], 401);
        }

        $isValidpass = Hash::check($password, $user->password);
        if (!$isValidpass) {
            return response()->json(['message' => 'LOGIN FAILED!'], 401);
        }

        $generateToken = bin2hex(random_bytes(40));
        $user->update([
            'token' => $generateToken
        ]);

        return response()->json(['message' => 'Login Success',
            'data token' => $user->token], 200);

    }
}
