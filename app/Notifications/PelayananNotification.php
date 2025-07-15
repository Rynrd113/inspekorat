<?php

namespace App\Notifications;

use App\Models\Pelayanan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PelayananNotification extends Notification
{
    use Queueable;

    protected $pelayanan;
    protected $action;

    /**
     * Create a new notification instance.
     */
    public function __construct(Pelayanan $pelayanan, string $action)
    {
        $this->pelayanan = $pelayanan;
        $this->action = $action;
    }

    /**
     * Get the notification's delivery channels.
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
        return (new MailMessage)
            ->subject('Pelayanan ' . ucfirst($this->action) . ' - ' . $this->pelayanan->nama)
            ->line('Pelayanan "' . $this->pelayanan->nama . '" telah ' . $this->action . '.')
            ->line('Deskripsi: ' . $this->pelayanan->deskripsi)
            ->line('Persyaratan: ' . $this->pelayanan->persyaratan)
            ->line('Waktu Pelayanan: ' . $this->pelayanan->waktu_pelayanan)
            ->action('Lihat Detail', url('/admin/pelayanan/' . $this->pelayanan->id))
            ->line('Terima kasih telah menggunakan aplikasi kami!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'pelayanan_id' => $this->pelayanan->id,
            'pelayanan_nama' => $this->pelayanan->nama,
            'action' => $this->action,
            'created_at' => now(),
        ];
    }
}
