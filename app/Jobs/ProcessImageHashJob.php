<?php

namespace App\Jobs;

use App\Models\Image;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class ProcessImageHashJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    protected int $imageId;

    public function __construct(int $imageId)
    {
        $this->imageId = $imageId;
    }

    public function handle(): void
    {
        try {
            $image = Image::find($this->imageId);
            if (! $image) {
                return;
            }

            $path = storage_path('app/' . $image->path);
            
            $pHash = substr(hash_file('md5', $path), 0, 16);

            $similar = Image::where('hash', $pHash)->first();

            if ($similar) {
                $image->category_id = $similar->category_id;
            } else {
                $image->category_id = $image->id;
            }

            $image->hash = $pHash;
            $image->save();
        } catch (\Exception $e) {
            Log::error("ProcessImageHashJob error: " . $e->getMessage());
            throw $e;
        }
    }
}
