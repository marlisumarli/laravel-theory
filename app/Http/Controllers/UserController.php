<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function createRecipe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'required',
            'video' => 'required',
            'user_email' => 'required',
            'ingredients' => 'required',
            'tools' => 'required',
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            return messageError($validator->messages()->toArray());
        }

        $thumbnail = $request->file('image');

        $fileName = now()->timestamp . '_' . $request->image->getClientOriginalName();
        $thumbnail->move('uploads', $fileName);

        $recipeData = $validator->validated();

        $recipe = Recipe::create([
            'title' => $recipeData['title'],
            'image' => 'uploads/' . $fileName,
            'description' => $recipeData['description'],
            'video' => $recipeData['video'],
            'user_email' => $recipeData['user_email'],
            'status' => $recipeData['status']
        ]);

        foreach (json_decode($request->ingredients) as $ingredient) {

            Ingredient::create([
                'name' => $ingredient->name,
                'unit' => $ingredient->unit,
                'quantity' => $ingredient->quantity,
                'description' => $ingredient->description,
                'recipe_id' => $recipe->id
            ]);
        }

        foreach (json_decode($request->tools) as $tool) {
            Tool::create([
                'name' => $tool->name,
                'description' => $tool->description,
                'recipe_id' => $recipe->id
            ]);
        }

        return response()->json([
            'data' => [
                'message' => 'recipe successfully stored',
                'recipe' => $recipeData['title']
            ]
        ]);
    }
}
