<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request) {
        $request->validate([
            'first_name' => 'required', 
            'last_name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required|confirmed'
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        return response()->json([
            'message' => "Account successfully created."
        ]);
    }

    public function login(Request $request){
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        if (!Auth::attempt($request->only(['email', 'password']))) {
            return response()->json([
                'message' => 'Invalid Credentials',
                'error_code' => 401
            ], 401);
        }
        
        $user = $request->user();
        $user->tokens()->delete();
        $token = $user->createToken('access_token', ['user']);

        return response()->json([
            'message' => 'Login success',
            'user' => $user,
            'access_token' => $token->plainTextToken
        ]);
        
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();

        return response()->json([
            'message' => 'Logout success'
        ], 200);
    }
}
