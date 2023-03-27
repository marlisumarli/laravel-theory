<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RecipeController extends Controller
{
    public function showRecipe(): JsonResponse
    {
        $recipes = Recipe::with('user')->where('status', 'published')->get();

        $data = [];

        foreach ($recipes as $recipe){
            $data[] = [
                'id' => $recipe->id,
                'title' => $recipe->title,
                'description' => $recipe->description,
                'image' => $recipe->image,
                'video' => $recipe->video,
                'user' => $recipe->user->name,
            ];
        }

        return response()->json($data, 200);
    }

    public function showRecipeById($id): JsonResponse
    {
       // TODO: Implement showRecipeById() method.
    }
}
