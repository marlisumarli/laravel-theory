<?php

namespace App\Observers;

use App\Models\Recipe;
use App\Models\Log;

class RecipeObserver
{
    /**
     * Handle the Recipe "created" event.
     *
     * @param Recipe $recipe
     * @return void
     */
    public function created(Recipe $recipe)
    {
        Log::create([
            'module' => 'add recipe',
            'action' => 'add recipe ' . $recipe->judul . ' with id ' . $recipe->idresep,
            'useraccess' => $recipe->user_email
        ]);
    }

    /**
     * Handle the Recipe "updated" event.
     *
     * @param Recipe $recipe
     * @return void
     */
    public function updated(Recipe $recipe)
    {
        Log::create([
            'module' => 'edit recipe',
            'action' => 'edit the recipe to be ' . $recipe->judul . ' with id' . $recipe->idresep,
            'useraccess' => $recipe->user_email
        ]);
    }

    /**
     * Handle the Recipe "deleted" event.
     *
     * @param Recipe $recipe
     * @return void
     */
    public function deleted(Recipe $recipe)
    {
        Log::create([
            'module' => 'delete recipe',
            'action' => 'delete the recipe ' . $recipe->judul . ' with id ' . $recipe->idresep,
            'useraccess' => $recipe->user_email
        ]);
    }

    /**
     * Handle the Recipe "restored" event.
     *
     * @param Recipe $recipe
     * @return void
     */
    public function restored(Recipe $recipe)
    {
        //
    }

    /**
     * Handle the Recipe "force deleted" event.
     *
     * @param Recipe $recipe
     * @return void
     */
    public function forceDeleted(Recipe $recipe)
    {
        //
    }
}
