<?php

namespace App\Listeners\Users;

use App\Events\Users\UserRegistered;
use App\Models\Users\UserLog;

class LogUserActivity
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle($event): void
    {
        UserLog::insert([
            'action' => $this->getAction($event),
            'user_id' => $event->user->id,
        ]);
    }

    private function getAction($event): string
    {
        return match (true) {
            $event instanceof UserRegistered => 'registered',
            default => 'unknown',
        };
    }
}
