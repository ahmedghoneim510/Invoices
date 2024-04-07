<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AddInvoice extends Notification implements ShouldQueue
{
    use Queueable;
    public $invoice_id;

    /**
     * Create a new notification instance.
     */
    public function __construct($invoice)
    {
        $this->invoice_id=$invoice;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        //dd($notifiable); // it 'll return user (notifiable)

        return (new MailMessage) // this will redirect to use info in env
        ->subject("New Order 1") // send number of order
        ->from('notification@ajyal-store.eg') // if it not exist it 'll use defult in env file
        ->greeting("Hi ") // $user->name
        ->action('view order', url('/')) // redirect
        ->line('Thank you for using our application!');// paragraph
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
