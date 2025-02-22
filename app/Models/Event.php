<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    /** @use HasFactory<\Database\Factories\EventFactory> */
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($event) {
            $event->slug = static::generateUniqueSlug($event->slug);
        });
    }

    private static function generateUniqueSlug(string $slug): string
    {
        $baseSlug = $slug;
        $counter = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    public function organizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
