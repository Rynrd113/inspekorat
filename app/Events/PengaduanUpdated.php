<?php

namespace App\Events;

use App\Models\Pengaduan;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PengaduanUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $pengaduan;
    public $oldStatus;
    public $newStatus;
    public $changedFields;

    /**
     * Create a new event instance.
     */
    public function __construct(Pengaduan $pengaduan, ?string $oldStatus = null, array $changedFields = [])
    {
        $this->pengaduan = $pengaduan;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $pengaduan->status;
        $this->changedFields = $changedFields;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}