<?php

namespace App\Listeners;

use App\Events\InvoicesCreated;
use App\Notifications\AddInvoice;
use App\Notifications\AddInvoiceNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class AddedInvoice
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
    public function handle(InvoicesCreated $event): void
    {
        $invoice= $event->invoice;
        $users= $event->users;
        Notification::send($users, new AddInvoiceNotification($invoice));
    }
}
