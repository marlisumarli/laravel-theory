<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\Tool;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function createRecipe(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'judul' => 'required',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'cara_pembuatan' => 'required',
            'video' => 'required',
            'user_email' => 'required|email',
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
            'status_resep' => 'draft'
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
}
