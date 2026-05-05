<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class RestockNotification extends Notification
{

    protected $item;

    public function __construct($item)
    {
        $this->item = $item;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
{
    return (new MailMessage)
        ->subject('Notifikasi Restok Varian Produk')
        ->line('Stok varian produk hampir habis!')
        ->line('Produk: ' . $this->item->product->name)
        ->line('Varian: ' . $this->item->name) // misal warna/ukuran
        ->line('Sisa Stok: ' . $this->item->stock)
        ->line('Segera lakukan restok.');
}
}
