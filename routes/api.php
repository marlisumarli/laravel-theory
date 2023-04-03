<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('register', [\App\Http\Controllers\AuthController::class, 'register']);
Route::post('login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::get('recipes', [\App\Http\Controllers\RecipeController::class, 'showRecipe']);
Route::post('recipes/get-recipe', [\App\Http\Controllers\RecipeController::class, 'showRecipeById']);
Route::post('recipes/rating', [\App\Http\Controllers\RecipeController::class, 'ratingRecipe']);

Route::middleware(['admin.api'])->prefix('admin')->group(function (){
    Route::post('register', [\App\Http\Controllers\AdminController::class, 'register']);
    Route::get('register', [\App\Http\Controllers\AdminController::class, 'showRegister']);
    Route::get('register/{id}', [\App\Http\Controllers\AdminController::class, 'showRegisterById']);
    Route::put('register/{id}', [\App\Http\Controllers\AdminController::class, 'updateRegisterById']);
    Route::delete('register/{id}', [\App\Http\Controllers\AdminController::class, 'deleteRegisterById']);
    Route::get('activation-account/{id}', [\App\Http\Controllers\AdminController::class, 'activationRegisterById']);
    Route::get('deactivation-account/{id}', [\App\Http\Controllers\AdminController::class, 'deactivationRegisterById']);

    Route::post('create-recipe', [\App\Http\Controllers\AdminController::class, 'createRecipe']);
    Route::put('update-recipe/{id}', [\App\Http\Controllers\AdminController::class, 'updateRecipe']);
    Route::delete('delete-recipe/{id}', [\App\Http\Controllers\AdminController::class, 'deleteRecipe']);
    Route::get('publish/{id}', [\App\Http\Controllers\AdminController::class, 'publishedRecipe']);
    Route::get('unpublish/{id}', [\App\Http\Controllers\AdminController::class, 'unpublishedRecipe']);

    Route::get('dashboard', [\App\Http\Controllers\AdminController::class, 'dashboard']);
});

Route::middleware(['user.api'])->prefix('user')->group(function (){
    Route::post('submit-recipe', [\App\Http\Controllers\UserController::class, 'createRecipe']);
});
