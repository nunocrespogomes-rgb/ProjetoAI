<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderCreatedPending extends Notification implements ShouldQueue
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
        $url = route('orders.show', $this->order);

        return (new MailMessage)
            ->greeting('Olá, ' . $notifiable->name . '!')
            ->subject('Encomenda #' . $this->order->id . ' em Processamento')
            ->line('Informamos que a tua encomenda foi criada com sucesso e encontra-se atualmente no estado PENDENTE.')
            ->line('O teu pedido já está a ser processado pela nossa equipa.')
            ->line('Valor do Pedido: ' . number_format($this->order->total_price, 2) . '€')
            ->action('Ver Encomenda na Loja', $url)
            ->line('Obrigado por escolheres a FunShirt!');
    }
}