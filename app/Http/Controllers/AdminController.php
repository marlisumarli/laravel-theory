<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8',
                'password_confirmation' => 'required|same:password',
                'role' => 'required|in:admin,user',
                'status' => 'required|in:active,inactive',
                'email_verified_at' => 'required|date'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()
                ], 400);
            }

            $user = $validator->validated();

            User::create($user);

        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage()
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully'
        ], 200);
    }

    public function showRegister()
    {
        $users = User::where('role', 'user')->get();

        return response()->json([
            'data' => [
                'message' => 'User created successfully',
                'data' => $users
            ]
        ], 200);
    }
    // TODO Lihat Detail Akun
}
