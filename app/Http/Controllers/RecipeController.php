<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\Rating;
use App\Models\Recipe;
use App\Models\RecipeView;
use App\Models\Tool;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RecipeController extends Controller
{
    public function showRecipe(): JsonResponse
    {
        $recipes = Recipe::with('user')->where('status_resep', 'published')->get();

        $data = [];

        foreach ($recipes as $recipe) {
            $data[] = [
                'idresep' => $recipe->idresep,
                'judul' => $recipe->judul,
                'cara_pembuatan' => $recipe->cara_pembuatan,
                'gambar' => $recipe->gambar,
                'video' => $recipe->video,
                'user' => $recipe->user->nama,
            ];
        }

        return response()->json($data, 200);
    }

    public function showRecipeById(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'idresep' => 'required'
        ]);

        $recipe = Recipe::with('user')->where('status_resep', 'published')->where('idresep', $request->idresep)->first();

        if (!$recipe) {
            return response()->json([
                'status' => 'error',
                'message' => 'Recipe not found'
            ], 404);
        }

        if ($validator->fails()){
            return messageError($validator->messages()->toArray());
        }

        $recipes = Recipe::where('status_resep', 'published')
            ->where('idresep', $request->idresep)->get();

        $tools = Tool::where('resep_idresep', $request->idresep)->get();
        $ingredients = Ingredient::where('resep_idresep', $request->idresep)->get();

        $data = [];

        foreach ($recipes as $recipe) {
            $data[] = [
                'idresep' => $recipe->idresep,
                'judul' => $recipe->judul,
                'gambar' => $recipe->gambar,
                'cara_pembuatan' => $recipe->cara_pembuatan,
                'video' => $recipe->video,
                'user' => $recipe->user->nama,
            ];
        }

        $recipeData = [
            'recipe' => $data,
            'tools' => $tools,
            'ingredients' => $ingredients,
        ];

        RecipeView::create([
            'email' => $request->email,
            'resep_idresep' => $request->idresep
        ]);

        return response()->json($recipeData, 200);
    }

    public function ratingRecipe(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'idresep' => 'required',
            'email' => 'email',
            'rating' => 'required|in:1,2,3,4,5',
        ]);

        if ($validator->fails()){
            return messageError($validator->messages()->toArray());
        }

        Rating::create([
            'resep_idresep' => $request->idresep,
            'rating' => $request->rating,
            'review' => $request->review,
            'user_email' => $request->email
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Rating Successfully'
        ], 200);
    }
}
