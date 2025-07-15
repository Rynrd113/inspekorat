<?php

namespace App\Events;

use App\Models\Pelayanan;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PelayananCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $pelayanan;

    public function __construct(Pelayanan $pelayanan)
    {
        $this->pelayanan = $pelayanan;
    }
}
