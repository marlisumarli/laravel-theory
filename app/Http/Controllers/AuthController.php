<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'confirmation_password' => 'required|same:password'
        ]);

        if ($validator->fails()) {
            return messageError($validator->messages()->toArray());
        }

        $user = $validator->validate();

        User::create($user);

        $payload = [
            'nama' => $user['nama'],
            'role' => 'user',
            'iat' => now()->timestamp,
            'exp' => now()->timestamp + 172000
        ];

        $token = JWT::encode($payload, env('JWT_SECRET_KEY'), 'HS256');

        Log::create([
            'module' => 'Login',
            'action' => 'Account Login',
            'useraccess' => $user['email']
        ]);

        return response()->json([
            'data' => [
                'message' => 'Successful registration',
                'name' => $user['nama'],
                'email' => $user['email'],
                'role' => 'user'
            ],
            'token' => "Bearer {$token}"
        ], 200);

    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);

        if ($validator->fails()) {
            return messageError($validator->messages()->toArray());
        }

        if (Auth::attempt($validator->validate())) {
            $payload = [
                'nama' => Auth::user()->nama,
                'role' => Auth::user()->role,
                'iat' => now()->timestamp,
                'exp' => now()->timestamp + 172000
            ];

            $token = JWT::encode($payload, env('JWT_SECRET_KEY'), 'HS256');

            Log::create([
                'module' => 'login',
                'action' => 'account login',
                'useraccess' => Auth::user()->email
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Login Successfully',
                "data" => [
                    'nama' => Auth::user()->nama,
                    'email' => Auth::user()->email,
                    'role' => Auth::user()->role
                ],
                'token' => "Bearer {$token}"
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Email Or Password Is Incorrect'
        ], 401);
    }
}
