<?php

namespace App\Listeners\Core\Auth;

use App\Events\Core\Auth\PasswordGenerationEvent;
use App\Mail\Core\Auth\PasswordGenerationMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PasswordGenerationListener implements ShouldQueue
{
    // Enables the listener to interact with the queue (delayed execution, retries, etc.)
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param PasswordGenerationEvent $event The event instance containing the user and generated password
     */
    public function handle(PasswordGenerationEvent $event): void
    {
        // Retrieve the user and password from the event payload
        $user = $event->user;
        $password = $event->password;

        // Send a password generation email to the user
        Mail::to($user->email)->send(new PasswordGenerationMail($user, $password));
    }
}
