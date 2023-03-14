<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function register(Request $request): JsonResponse
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

    public function showRegister(): JsonResponse
    {
        $users = User::where('role', 'user')->get();

        return response()->json([
            'data' => [
                'message' => 'Users registered',
                'data' => $users
            ]
        ], 200);
    }

    public function showRegisterById($id): JsonResponse
    {
        $user = User::where('id', $id)->first();

        return response()->json([
            'data' => [
                'message' => "User id: $id",
                'data' => $user
            ]
        ], 200);
    }

    public function updateRegisterById($id, Request $request): JsonResponse
    {
        $user = User::find($id);

        $response = response()->json([
            'data' => [
                'message' => "null"
            ]
        ], 200);

        try {
            if ($user) {
                $validator = Validator::make($request->all(), [
                    'name' => 'required',
                    'password' => 'min:8',
                    'email' => 'required|email|unique:users,email,' . $id,
                    'confirmation_password' => 'same:password',
                    'role' => 'required|in:admin,user',
                    'status' => 'required|in:active,inactive',
                    'email_validate' => 'required|email'
                ]);

                if ($validator->fails()) {
                    return messageError($validator->messages()->toArray());
                }

                $data = $validator->validated();

                User::where('id', $id)->update($data);

                $response = response()->json([
                    'data' => [
                        'message' => "User with id: $id successfully updated",
                        'name' => $data['name'],
                        'email' => $data['email'],
                        'role' => $data['role']
                    ]
                ], 200);
            } else {
                $response = response()->json([
                    'data' => [
                        'message' => "User with id: $id not found"
                    ]
                ], 404);
            }

        } catch (\Exception $exception) {
            $response = response()->json([
                'status' => 'error',
                'message' => $exception->getMessage()
            ], 402);
        }
        return $response;
    }
}
