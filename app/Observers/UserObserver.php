<?php

namespace App\Observers;

use App\Models\Log;
use App\Models\User;

class UserObserver
{

    public function creating(User $user)
    {
        $user->last_login = now();
    }

    /**
     * Handle the User "created" event.
     *
     * @param \App\Models\User $user
     * @return void
     */
    public function created(User $user)
    {
        Log::create([
            'module' => 'register',
            'action' => 'account register',
            'user_access' => $user->email
        ]);
    }

    /**
     * Handle the User "updated" event.
     *
     * @param \App\Models\User $user
     * @return void
     */
    public function updated(User $user)
    {
        Log::create([
            'module' => 'update',
            'action' => 'account update',
            'user_access' => $user->email
        ]);
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param \App\Models\User $user
     * @return void
     */
    public function deleted(User $user)
    {
        //
    }

    /**
     * Handle the User "restored" event.
     *
     * @param \App\Models\User $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param \App\Models\User $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
