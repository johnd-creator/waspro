<?php

namespace App\Events;

use App\Models\LogPenyimpananLimbah;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WasteDocumentUploaded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public LogPenyimpananLimbah $log,
        public string $path,
        public string $originalName,
        public int $size,
    ) {
        //
    }
}
