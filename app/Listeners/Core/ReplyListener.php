<?php

namespace App\Listeners\Core;

use App\Events\Core\ReplyEvent;
use App\Mail\Core\ReplyMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class ReplyListener
{
    /**
     * Create the event listener.
     *
     * Constructor method for the listener.
     * Currently unused but can be used for dependency injection.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param ReplyEvent $event The event containing reply data
     */
    public function handle(ReplyEvent $event): void
    {
        // Retrieve the reply data array from the event
        $reply = $event->reply;

        // Send the reply email using the provided email address in the reply data
        Mail::to($reply["email"])->send(new ReplyMail($reply));
    }
}
