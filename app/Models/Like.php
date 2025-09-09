<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Log;

class Like extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','action'];

    public function likeable()
    {
        try {
            return $this->morphTo();
        } catch (\Exception $e) {
            Log::error("Like::likeable error: " . $e->getMessage());
            return null;
        }
    }
}
