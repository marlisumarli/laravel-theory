<?php

namespace App\Http\Controllers;

use App\Models\log;
use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
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

            Log::create([
                'module' => 'login',
                'action' => 'account login',
                'user_access' => $user['email']
            ]);

            return response()->json([
                'data' => [
                    'message' => 'Successful login',
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
}
