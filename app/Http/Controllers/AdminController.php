<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Recipe;
use App\Models\Ingredient;
use App\Models\Tool;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'password_confirmation' => 'required|same:password',
            'role' => 'required|in:admin,user',
            'status' => 'required|in:aktif,non-aktif',
            'email_verified_at' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'Error',
                'message' => $validator->errors()
            ], 400);
        }

        $user = $validator->validated();

        User::create($user);

        return response()->json([
            'status' => 'Success',
            'message' => 'User Successfully Created',
            'data' => [
                'User' => $user
            ],
        ], 200);
    }

    public function showRegister(): JsonResponse
    {
        $users = User::where('role', 'user')->get();

        return response()->json([
            'status' => 'Success',
            'message' => 'Registered Users',
            'data' => [
                'users' => $users
            ]
        ], 200);
    }

    public function showRegisterById($id): JsonResponse
    {
        $user = User::find($id);

        if ($user) {
            return response()->json([
                'status' => 'Success',
                "message" => "User Registered",
                "data" => [
                    'users' => $user
                ]
            ], 200);
        } else {
            return response()->json([
                'status' => 'Error',
                "message" => "Not Found"
            ], 404);
        }
    }

    public function updateRegisterById($id, Request $request): JsonResponse
    {
        $user = User::find($id);

        if ($user) {
            $validator = Validator::make($request->all(), [
                'nama' => 'required',
                'password' => 'min:8',
                'email' => 'required|email|unique:users,email,' . $id,
                'confirmation_password' => 'same:password',
                'role' => 'required|in:admin,user',
                'status' => 'required|in:aktif,non-aktif',
                'email_validate' => 'required|email'
            ]);

            if ($validator->fails()) {
                return messageError($validator->messages()->toArray());
            }

            $data = $validator->validated();

            User::where('id', $id)->update($data);

            $response = response()->json([
                'status' => 'Success',
                'message' => "User Updated Successfully",
                'data' => [
                    'user' => [
                        $data
                    ]
                ]
            ], 200);
        } else {
            $response = response()->json([
                'status' => 'Error',
                'message' => "Not Found"
            ], 404);
        }

        return $response;
    }

    public function deleteRegisterById($id): JsonResponse
    {
        $user = User::find($id);

        if ($user) {
            User::where('id', $id)->delete();

            $response = response()->json([
                'status' => 'Success',
                'message' => "User Deleted Successfully",
            ], 200);
        } else {
            $response = response()->json([
                'status' => 'Error',
                'message' => "Not Found"
            ], 404);
        }

        return $response;
    }

    public function activationRegisterById($id): JsonResponse
    {
        $user = User::find($id);

        if ($user) {
            User::where('id', $id)->update(['status' => 'aktif']);

            return response()->json([
                'status' => 'Success',
                'message' => "Activated Successfully",
                'data' => [
                    'user' => $user
                ]
            ], 200);

        } else {
            return response()->json([
                'status' => 'Error',
                'message' => "Not Found"
            ], 404);
        }
    }

    public function deactivationRegisterById($id): JsonResponse
    {
        $user = User::find($id);

        if ($user) {
            User::where('id', $id)->update(['status' => 'non-aktif']);

            return response()->json([
                'status' => 'Success',
                'message' => "User Successfully Deactivated",
                'data' => [
                    'user' => $user
                ]
            ], 200);

        } else {
            return response()->json([
                'status' => 'Error',
                'message' => "Not Found"
            ], 404);
        }
    }

    public function createRecipe(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'cara_pembuatan' => 'required',
            'video' => 'required',
            'user_email' => 'required',
            'bahan' => 'required',
            'alat' => 'required'
        ]);

        if ($validator->fails()) {
            return messageError($validator->messages()->toArray());
        }

        $thumbnail = $request->file('gambar');

        $fileName = now()->timestamp . '_' . $request->gambar->getClientOriginalName();
        $thumbnail->move('uploads', $fileName);

        $recipeData = $validator->validated();

        $recipe = Recipe::create([
            'judul' => $recipeData['judul'],
            'gambar' => 'uploads/' . $fileName,
            'cara_pembuatan' => $recipeData['cara_pembuatan'],
            'video' => $recipeData['video'],
            'user_email' => $recipeData['user_email'],
            'status_resep' => 'submit'
        ]);

        foreach (json_decode($request->bahan) as $ingredient) {

            Ingredient::create([
                'nama' => $ingredient->nama,
                'satuan' => $ingredient->satuan,
                'banyak' => $ingredient->banyak,
                'keterangan' => $ingredient->keterangan,
                'resep_idresep' => $recipe->id
            ]);
        }

        foreach (json_decode($request->alat) as $tool) {
            Tool::create([
                'nama_alat' => $tool->nama,
                'keterangan' => $tool->keterangan,
                'resep_idresep' => $recipe->id
            ]);
        }

        return response()->json([
            'status' => 'Success',
            'message' => 'Recipe Stored Successfully',
            'data' => [
                'recipe_id' => $recipe->id,
                'recipe_title' => $recipeData['judul'],
            ]
        ]);

    }

    public function updateRecipe(Request $request, $id): JsonResponse
    {
        $recipe = Recipe::where('idresep', $id)->first();

        if (!$recipe) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Not Found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'judul' => 'required',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'cara_pembuatan' => 'required',
            'video' => 'required',
            'user_email' => 'required',
            'bahan' => 'required',
            'alat' => 'required'
        ]);

        if ($validator->fails()) {
            return messageError($validator->messages()->toArray());
        }

        $thumbnail = $request->file('gambar');

        $fileName = now()->timestamp . '_' . $request->gambar->getClientOriginalName();
        $thumbnail->move('uploads', $fileName);

        $recipeData = $validator->validated();

        Recipe::where('idresep', $id)->update([
            'judul' => $recipeData['judul'],
            'gambar' => 'uploads/' . $fileName,
            'cara_pembuatan' => $recipeData['cara_pembuatan'],
            'video' => $recipeData['video'],
            'user_email' => $recipeData['user_email'],
            'status_resep' => 'submit'
        ]);

        Ingredient::where('resep_idresep', $id)->delete();
        Tool::where('resep_idresep', $id)->delete();

        foreach (json_decode($request->bahan) as $ingredient) {
            Ingredient::create([
                'nama' => $ingredient->nama,
                'satuan' => $ingredient->satuan,
                'banyak' => $ingredient->banyak,
                'keterangan' => $ingredient->keterangan,
                'resep_idresep' => $id
            ]);
        }

        foreach (json_decode($request->alat) as $tool) {
            Tool::create([
                'nama_alat' => $tool->nama_alat,
                'keterangan' => $tool->keterangan,
                'resep_idresep' => $id
            ]);
        }

        return response()->json([
            'status' => 'Success',
            'message' => 'Recipe Edited Successfully',
            'data' => [
                'recipe' => $recipeData['judul']
            ]
        ], 200);

    }

    public function deleteRecipe($id): JsonResponse
    {
        $recipe = Recipe::where('idresep', $id)->first();

        if (!$recipe) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Not Found'
            ], 404);
        }

        Tool::where('resep_idresep', $id)->delete();
        Ingredient::where('resep_idresep', $id)->delete();
        Recipe::where('idresep', $id)->delete();

        return response()->json([
            'status' => 'Success',
            'message' => 'Recipe Deleted Successfully'
        ], 200);
    }

    public function publishedRecipe($id): JsonResponse
    {
        $recipe = Recipe::where('idresep', $id)->first();

        if (!$recipe) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Not Found'
            ], 404);
        }

        Recipe::where('idresep', $id)->update(['status_resep' => 'published']);

        Log::create([
            'module' => 'Recipe Published',
            'action' => 'Published Recipe With ID : ' . $id,
            'useraccess' => 'Administrator'
        ]);

        return response()->json([
            'status' => 'Success',
            'message' => "Recipe Successfully Published",
            'data' => [
                'recipe_id' => $id,
                'recipe_title' => $recipe['judul'],
            ]
        ], 200);

    }

    public function unpublishedRecipe($id): JsonResponse
    {
        $recipe = Recipe::where('idresep', $id)->first();

        if (!$recipe) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Not Found'
            ], 404);
        }

        Recipe::where('idresep', $id)->update(['status_resep' => 'unpublished']);

        Log::create([
            'module' => 'Recipe Unpublished',
            'action' => 'Unpublished Recipe With ID : ' . $id,
            'useraccess' => 'Administrator'
        ]);

        return response()->json([
            'status' => 'Success',
            'message' => "Recipe Successfully Unpublished",
            'data' => [
                'recipe_id' => $id,
                'recipe_title' => $recipe['judul']
            ]
        ], 200);

    }

    public function dashboard(): JsonResponse
    {
        $totalRecipe = Recipe::count();
        $totalUser = User::count();
        $totalPublished = Recipe::where('status_resep', 'published')->count();

        // Popular Recipe from table recipe_view
        $popularRecipe = DB::table('recipes')
            ->select('judul', DB::raw("COUNT(*) as 'Total Views'"))
            ->join('recipe_views', 'recipes.idresep', '=', 'recipe_views.resep_idresep')
            ->groupBy('judul')
            ->orderByRaw('COUNT(*) DESC')
            ->limit(10)
            ->get();
        return response()->json([
            'data' => [
                'total_recipe' => $totalRecipe,
                'total_user' => $totalUser,
                'total_published' => $totalPublished,
                'popular_recipe' => $popularRecipe
            ]
        ], 200);
    }
}
