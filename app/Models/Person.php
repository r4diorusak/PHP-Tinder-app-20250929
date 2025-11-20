<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Person extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'age',
        'pictures',
        'location',
        'bio',
        'gender',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'pictures' => 'array',
    ];

    /**
     * Get people that this person has liked.
     */
    public function liked(): BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'likes', 'liker_id', 'liked_id')
            ->withTimestamps();
    }

    /**
     * Get people who have liked this person.
     */
    public function likedBy(): BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'likes', 'liked_id', 'liker_id')
            ->withTimestamps();
    }

    /**
     * Get people that this person has disliked.
     */
    public function disliked(): BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'dislikes', 'disliker_id', 'disliked_id')
            ->withTimestamps();
    }

    /**
     * Get people who have disliked this person.
     */
    public function dislikedBy(): BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'dislikes', 'disliked_id', 'disliker_id')
            ->withTimestamps();
    }

    /**
     * Check if this person has liked another person.
     */
    public function hasLiked(int $personId): bool
    {
        return $this->liked()->where('liked_id', $personId)->exists();
    }

    /**
     * Check if this person has disliked another person.
     */
    public function hasDisliked(int $personId): bool
    {
        return $this->disliked()->where('disliked_id', $personId)->exists();
    }

    /**
     * Check if this person has interacted with another person (liked or disliked).
     */
    public function hasInteractedWith(int $personId): bool
    {
        return $this->hasLiked($personId) || $this->hasDisliked($personId);
    }
}
