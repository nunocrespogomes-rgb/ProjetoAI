<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderClosedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {        
        $pathToFile = storage_path('app/private/pdf_receipts/receipt_' . $this->order->id . '.pdf');

        $email = (new MailMessage)
            ->greeting('Olá, ' . $notifiable->name . '!')
            ->subject('A tua Encomenda #' . $this->order->id . ' foi concluída!')
            ->line('Muito obrigado pela tua compra na FunShirt! A tua encomenda foi processada e encontra-se FECHADA.')
            ->line('Em anexo enviamos o recibo oficial em formato PDF para os teus registos.')
            ->line('Se tiveres alguma dúvida, não hesites em contactar-nos.');

        if (file_exists($pathToFile)) {
            $email->attach($pathToFile, [
                'as' => 'recibo-encomenda-' . $this->order->id . '.pdf',
                'mime' => 'application/pdf',
            ]);
        }

        return $email;
    }
}