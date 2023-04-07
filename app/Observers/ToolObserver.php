<?php

namespace App\Observers;

use App\Models\Log;
use App\Models\Tool;

class ToolObserver
{
    /**
     * Handle the Tool "created" event.
     *
     * @param  \App\Models\Tool  $tool
     * @return void
     */
    public function created(Tool $tool)
    {
        Log::create([
            'module' => 'add tool',
            'action' => 'add tool for recipe id ' . $tool->resep_idresep . ' with tool name ' . $tool->nama,
            'useraccess' => '-'
        ]);
    }

    /**
     * Handle the Tool "updated" event.
     *
     * @param  \App\Models\Tool  $tool
     * @return void
     */
    public function updated(Tool $tool)
    {
        //
    }

    /**
     * Handle the Tool "deleted" event.
     *
     * @param  \App\Models\Tool  $tool
     * @return void
     */
    public function deleted(Tool $tool)
    {
        Log::create([
            'module' => 'delete tool',
            'action' => 'delete tool for recipe id ' . $tool->resep_idresep . ' with tool name ' . $tool->nama,
            'useraccess' => '-'
        ]);
    }

    /**
     * Handle the Tool "restored" event.
     *
     * @param  \App\Models\Tool  $tool
     * @return void
     */
    public function restored(Tool $tool)
    {
        //
    }

    /**
     * Handle the Tool "force deleted" event.
     *
     * @param  \App\Models\Tool  $tool
     * @return void
     */
    public function forceDeleted(Tool $tool)
    {
        //
    }
}
