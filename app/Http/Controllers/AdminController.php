<?php

namespace App\Http\Controllers;

use App\Models\Formula;
use App\Models\Ingredient;
use App\Models\Tool;
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

    public function deleteRegisterById($id): JsonResponse
    {
        $user = User::find($id);

        try {
            if ($user) {
                User::where('id', $id)->delete();

                $response = response()->json([
                    'data' => [
                        'message' => "User with id: $id successfully deleted"
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

    public function activationRegisterById($id): JsonResponse
    {
        $user = User::find($id);

        if ($user) {
            User::where('id', $id)->update(['status' => 'active']);

            return response()->json([
                'data' => [
                    'message' => "User with id: $id successfully activated"
                ]
            ], 200);

        } else {
            return response()->json([
                'data' => [
                    'message' => "User with id: $id not found"
                ]
            ], 404);
        }
    }

    public function deactivationRegisterById($id): JsonResponse
    {
        $user = User::find($id);

        if ($user) {
            User::where('id', $id)->update(['status' => 'inactive']);

            return response()->json([
                'data' => [
                    'message' => "User with id: $id successfully deactivated"
                ]
            ], 200);

        } else {
            return response()->json([
                'data' => [
                    'message' => "User with id: $id not found"
                ]
            ], 404);
        }
    }

    public function createFormula(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'description' => 'required',
                'video' => 'required',
                'user_email' => 'required',
                'ingredients' => 'required',
                'tools' => 'required',
                'status' => 'required'
            ]);

            if ($validator->fails()){
                return messageError($validator->messages()->toArray());
            }

            $thumbnail = $request->file('image');

            $fileName = now()->timestamp . '_'. $request->image->getClientOriginalName();
            $thumbnail->move('uploads', $fileName);

            $formulaData = $validator->validated();

            $formula = Formula::create([
                'name' => $formulaData['name'],
                'image' => 'uploads/' . $fileName,
                'description' => $formulaData['description'],
                'video' => $formulaData['video'],
                'user_email' => $formulaData['user_email'],
                'status' => $formulaData['status']
            ]);

            foreach (json_decode($request->ingredients) as $ingredient){

                Ingredient::create([
                    'name' => $ingredient->name,
                    'unit' => $ingredient->unit,
                    'quantity' => $ingredient->quantity,
                    'description' => $ingredient->description,
                    'formula_id' => $formula->id
                ]);
            }

            foreach (json_decode($request->tools) as $tool){
                Tool::create([
                    'name' => $tool->name,
                    'description' => $tool->description,
                    'formula_id' => $formula->id
                ]);
            }

            return response()->json([
                'data' => [
                    'message' => 'Formula successfully stored',
                    'formula' => $formulaData['name']
                ]
            ]);

        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function updateFormula(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'required',
            'video' => 'required',
            'user_email' => 'required',
            'ingredients' => 'required',
            'tools' => 'required',
            'status' => 'required'
        ]);

        if ($validator->fails()){
            return messageError($validator->messages()->toArray());
        }

        $thumbnail = $request->file('image');

        $fileName = now()->timestamp . '_'. $request->image->ClientI();
        $thumbnail->move('uploads', $fileName);

        $formulaData = $validator->validated();

        Formula::where('formula_id', $id)->update([
            'name' => $formulaData['name'],
            'image' => 'uploads/' . $fileName,
            'description' => $formulaData['description'],
            'video' => $formulaData['video'],
            'user_email' => $formulaData['user_email'],
            'status' => $formulaData['status']
        ]);

        Ingredient::where('formula_id', $id)->delete();
        Tool::where('formula_id', $id)->delete();

        foreach (json_decode($request->ingredients) as $ingredient){
            Ingredient::create([
                'name' => $ingredient->name,
                'unit' => $ingredient->unit,
                'quantity' => $ingredient->quantity,
                'description' => $ingredient->description,
                'formula_id' => $id
            ]);
        }

        foreach (json_decode($request->tools) as $tool){
            Tool::create([
                'name' => $tool->name,
                'description' => $tool->description,
                'formula_id' => $id
            ]);
        }

        return response()->json([
            'data' => [
                'message' => 'formula edited successfully',
                'formula' => $formulaData['name']
            ]
        ], 200);
    }
}
