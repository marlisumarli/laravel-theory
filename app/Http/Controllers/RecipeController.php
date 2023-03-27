<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
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
        $recipes = Recipe::with('user')->where('status', 'published')->get();

        $data = [];

        foreach ($recipes as $recipe) {
            $data[] = [
                'recipe_id' => $recipe->recipe_id,
                'title' => $recipe->title,
                'description' => $recipe->description,
                'image' => $recipe->image,
                'video' => $recipe->video,
                'user' => $recipe->user->name,
            ];
        }

        return response()->json($data, 200);
    }

    public function showRecipeById(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'recipeId' => 'required',
            'email' => 'email'
        ]);

        if ($validator->fails()){
            return messageError($validator->messages()->toArray());
        }

        $recipes = Recipe::where('status', 'published')
            ->where('recipe_id', $request->recipeId)->get();

        $tools = Tool::where('recipe_id', $request->recipeId)->get();
        $ingredients = Ingredient::where('recipe_id', $request->recipeId)->get();

        $data = [];

        foreach ($recipes as $recipe) {
            $data[] = [
                'recipe_id' => $recipe->recipe_id,
                'title' => $recipe->title,
                'description' => $recipe->description,
                'image' => $recipe->image,
                'video' => $recipe->video,
                'user' => $recipe->user->name,
                'tools' => $tools,
                'ingredients' => $ingredients,
                'name' => $recipe->user->name,
            ];
        }

        $recipeData = [
            'recipe' => $data,
            'tools' => $tools,
            'ingredients' => $ingredients,
        ];

        RecipeView::create([
            'email' => $request->email,
            'recipe_id' => $request->recipeId
        ]);

        return response()->json($recipeData, 200);
    }
}
