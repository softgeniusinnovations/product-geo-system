<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Image extends Model
{
    use HasFactory;

    protected $fillable = ['path','hash','category_id','meta'];

    protected $casts = [
        'meta' => 'array'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
