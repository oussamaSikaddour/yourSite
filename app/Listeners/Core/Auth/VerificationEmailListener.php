<?php

namespace App\Listeners\Core\Auth;

use App\Events\Core\Auth\VerificationEmailEvent;
use App\Mail\Core\Auth\VerificationMail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class VerificationEmailListener
{
    /**
     * Handle the event.
     *
     * @param VerificationEmailEvent $event The event that was fired, containing the User model
     */
    public function handle(VerificationEmailEvent $event): void
    {
        // Retrieve the user from the event
        $user = $event->user;

        // Send the verification email to the user's email address
        Mail::to($user->email)->send(new VerificationMail($user));
    }
}
