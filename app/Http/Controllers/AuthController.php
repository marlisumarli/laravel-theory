<?php

namespace App\Http\Controllers;

use App\Models\log;
use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
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
                'name' => $user['name'],
                'role' => 'user',
                'iat' => now()->timestamp,
                'exp' => now()->timestamp + 7200
            ];

            $token = JWT::encode($payload, env('JWT_SECRET_KEY'), 'HS256');

            return response()->json([
                'data' => [
                    'message' => 'Successful registration',
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'role' => 'user'
                ],
                'token' => "Bearer {$token}"
            ], 200);
        } catch (\Exception $e) {
            return messageError('Error');
        }
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

        if (Auth::attempt($validator->validate())){
            $payload = [
                'name' => Auth::user()->name,
                'role' => Auth::user()->role,
                'iat' => now()->timestamp,
                'exp' => now()->timestamp + 7200
            ];

            $token = JWT::encode($payload, env('JWT_SECRET_KEY'), 'HS256');

            log::create([
                'module' => 'login',
                'action' => 'account login',
                'user_access' => Auth::user()->email
            ]);

            return response()->json([
                "data" => [
                    'message' => 'Successful login',
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'role' => Auth::user()->role
                ],
                'token' => "Bearer {$token}"
            ], 200);
        }

        return response()->json([
            'error' => 'Email or password is incorrect'
        ], 401);
    }
}
