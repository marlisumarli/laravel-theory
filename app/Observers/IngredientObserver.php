<?php

namespace App\Observers;

use App\Models\Ingredient;
use App\Models\Log;

class IngredientObserver
{
    /**
     * Handle the Ingredient "created" event.
     *
     * @param  \App\Models\Ingredient  $ingredient
     * @return void
     */
    public function created(Ingredient $ingredient)
    {
        Log::create([
            'module' => 'add ingredient',
            'action' => 'add in\gredient for formula id ' . $ingredient->formula_id . ' with ingredient ' . $ingredient->name,
            'user_access' => '-'
        ]);
    }

    /**
     * Handle the Ingredient "updated" event.
     *
     * @param  \App\Models\Ingredient  $ingredient
     * @return void
     */
    public function updated(Ingredient $ingredient)
    {
        //
    }

    /**
     * Handle the Ingredient "deleted" event.
     *
     * @param  \App\Models\Ingredient  $ingredient
     * @return void
     */
    public function deleted(Ingredient $ingredient)
    {
        //
    }

    /**
     * Handle the Ingredient "restored" event.
     *
     * @param  \App\Models\Ingredient  $ingredient
     * @return void
     */
    public function restored(Ingredient $ingredient)
    {
        //
    }

    /**
     * Handle the Ingredient "force deleted" event.
     *
     * @param  \App\Models\Ingredient  $ingredient
     * @return void
     */
    public function forceDeleted(Ingredient $ingredient)
    {
        //
    }
}
