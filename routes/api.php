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
Route::post('recipes', [\App\Http\Controllers\RecipeController::class, 'showRecipeById']);

Route::middleware(['admin.api'])->prefix('admin')->group(function (){
    Route::post('register', [\App\Http\Controllers\AdminController::class, 'register']);
    Route::get('register', [\App\Http\Controllers\AdminController::class, 'showRegister']);
    Route::get('register/{id}', [\App\Http\Controllers\AdminController::class, 'showRegisterById']);
    Route::put('register/{id}', [\App\Http\Controllers\AdminController::class, 'updateRegisterById']);
    Route::delete('register/{id}', [\App\Http\Controllers\AdminController::class, 'deleteRegisterById']);
    Route::get('register/activation/{id}', [\App\Http\Controllers\AdminController::class, 'activationRegisterById']);
    Route::get('register/deactivation/{id}', [\App\Http\Controllers\AdminController::class, 'deactivationRegisterById']);

    Route::post('recipes', [\App\Http\Controllers\AdminController::class, 'createRecipe']);
    Route::put('recipes/{id}', [\App\Http\Controllers\AdminController::class, 'updateRecipe']);
    Route::delete('recipes/{id}', [\App\Http\Controllers\AdminController::class, 'deleteRecipe']);
    Route::get('recipes/{id}/published', [\App\Http\Controllers\AdminController::class, 'publishedRecipe']);
    Route::get('recipes/{id}/unpublished', [\App\Http\Controllers\AdminController::class, 'unpublishedRecipe']);

    Route::get('dashboard', [\App\Http\Controllers\AdminController::class, 'dashboard']);
});
