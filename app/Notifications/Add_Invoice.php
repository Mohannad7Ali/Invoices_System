<?php

namespace App\Notifications;

use App\Models\Invoices;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class Add_Invoice extends Notification
{
    use Queueable;
    private $invoices ;

    /**
     * Create a new notification instance.
     */
    public function __construct(Invoices $invoices)
    {
        $this->invoices = $invoices ;
    }



    public function via(object $notifiable): array
    {
        return ['database'];
    }
    public function toDatabase($notifiable)
    {
        return [

            //'data' => $this->details['body']
            'id'=> $this->invoices->id,
            'title'=>'تم اضافة فاتورة جديد بواسطة :',
            'user'=> Auth::user()->name,

        ];
    }



}
