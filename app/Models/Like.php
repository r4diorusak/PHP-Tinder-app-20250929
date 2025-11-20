<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Like extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'liker_id',
        'liked_id',
    ];

    /**
     * Get the person who liked.
     */
    public function liker(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'liker_id');
    }

    /**
     * Get the person who was liked.
     */
    public function liked(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'liked_id');
    }
}
