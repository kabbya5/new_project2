<?php

namespace App\Listeners;

use App\Events\UserSessionChanged;
use Illuminate\Support\Facades\Cache;

class BroadCastUserLoginNotification
{
    /**
     * Handle the event.
     */
    public function handle(UserSessionChanged $event): void
    {
        // Cache key to store the state of the event
        $cacheKey = 'user_session_changed_flag';

        // Check if event is already processed
        if (Cache::has($cacheKey)) {
            return; // Prevent recursion if the event is already triggered
        }

        // Store the state to prevent further triggering of the event
        Cache::put($cacheKey, true, 60); // Store the flag for 60 seconds (or adjust as needed)

        // Add your event handling logic here
        \Log::info('User session changed: ' . $event->message);

        // Reset the flag after the logic is executed (to allow it to be triggered again later)
        Cache::forget($cacheKey);
    }
}
