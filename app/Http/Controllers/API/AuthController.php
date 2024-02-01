<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Mail\TestMail;
use Illuminate\Support\Facades\Mail;

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

    public function getProfile(Request $request)
    {
        $user = $request->user();
        return response()->json($user);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();

        return response()->json([
            'message' => 'Logout success'
        ], 200);
    }

    public function testMail(Request $request)
    {
        $data = [
            'name' => 'Joe Doe',
            'body' => "this is a test message"
        ];
        Mail::to('talashhh9@gmail.com')->send(new TestMail('test subject', $data));
    }
}
