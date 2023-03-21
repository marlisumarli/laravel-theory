<?php

namespace App\Observers;

use App\Models\Formula;
use App\Models\Log;

class FormulaObserver
{
    /**
     * Handle the Formula "created" event.
     *
     * @param  \App\Models\Formula  $formula
     * @return void
     */
    public function created(Formula $formula)
    {
        Log::create([
            'module' => 'add formula',
            'action' => 'add formula ' . $formula->name . ' with id ' . $formula->id,
            'user_access' => $formula->user_email
        ]);
    }

    /**
     * Handle the Formula "updated" event.
     *
     * @param  \App\Models\Formula  $formula
     * @return void
     */
    public function updated(Formula $formula)
    {
        Log::create([
            'module' => 'edit formula',
            'action' => 'edit the formula to be ' . $formula->name . ' with id' . $formula->id,
            'user_access' => $formula->user_email
        ]);
    }

    /**
     * Handle the Formula "deleted" event.
     *
     * @param  \App\Models\Formula  $formula
     * @return void
     */
    public function deleted(Formula $formula)
    {
        //
    }

    /**
     * Handle the Formula "restored" event.
     *
     * @param  \App\Models\Formula  $formula
     * @return void
     */
    public function restored(Formula $formula)
    {
        //
    }

    /**
     * Handle the Formula "force deleted" event.
     *
     * @param  \App\Models\Formula  $formula
     * @return void
     */
    public function forceDeleted(Formula $formula)
    {
        //
    }
}
