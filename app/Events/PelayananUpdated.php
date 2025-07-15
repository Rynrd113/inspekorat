<?php

namespace App\Events;

use App\Models\Pelayanan;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PelayananUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $pelayanan;
    public $oldValues;

    public function __construct(Pelayanan $pelayanan, array $oldValues = [])
    {
        $this->pelayanan = $pelayanan;
        $this->oldValues = $oldValues;
    }
}
