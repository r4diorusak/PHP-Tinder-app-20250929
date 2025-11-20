<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dislike extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'disliker_id',
        'disliked_id',
    ];

    /**
     * Get the person who disliked.
     */
    public function disliker(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'disliker_id');
    }

    /**
     * Get the person who was disliked.
     */
    public function disliked(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'disliked_id');
    }
}
