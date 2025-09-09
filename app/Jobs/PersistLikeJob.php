<?php

namespace App\Jobs;

use App\Models\Like;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class PersistLikeJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public array $payload;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    public function handle(): void
    {
        try {
            $data = $this->payload;

            Like::updateOrCreate(
                [
                    'user_id' => $data['user_id'],
                    'likeable_type' => $data['entity_type'],
                    'likeable_id' => $data['entity_id'],
                ],
                ['action' => $data['action']]
            );
        } catch (\Exception $e) {
            Log::error("PersistLikeJob error: " . $e->getMessage());
            throw $e;
        }
    }
}
