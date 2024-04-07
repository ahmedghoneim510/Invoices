<?php

namespace App\Listeners;

use App\Events\InvoicesCreated;
use App\Notifications\AddInvoice;
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
        $id= $event->invoice_id;
        $user= Auth()->user();
        $user->notify(new AddInvoice($id));// it's 'll send user as notifiable

    }
}
