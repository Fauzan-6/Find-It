<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Item extends Model
{
    protected $fillable = [
        'user_id', 'title', 'type', 'category', 'description', 'campus_location',
        'date_occurred', 'image', 'moderation_status', 'item_status',
    ];

    protected function casts(): array
    {
        return ['date_occurred' => 'date'];
    }

    /* ---------- Relasi ---------- */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function claims(): HasMany
    {
        return $this->hasMany(Claim::class);
    }

    /* ---------- Scopes ---------- */
    // Barang yang boleh tampil di katalog publik
    public function scopePublicCatalog(Builder $q): Builder
    {
        return $q->where('moderation_status', 'approved')->where('item_status', 'active');
    }

    public function scopeFilter(Builder $q, array $f): Builder
    {
        return $q
            ->when($f['q'] ?? null, fn ($q, $v) => $q->where(function ($w) use ($v) {
                $w->where('title', 'like', "%{$v}%")->orWhere('description', 'like', "%{$v}%");
            }))
            ->when($f['category'] ?? null, fn ($q, $v) => $q->where('category', $v))
            ->when($f['type'] ?? null, fn ($q, $v) => $q->where('type', $v))
            ->when($f['location'] ?? null, fn ($q, $v) => $q->where('campus_location', $v));
    }

    /* ---------- Accessors ---------- */
    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? Storage::disk('public')->url($this->image) : null;
    }

    public function isOwnedBy(?User $user): bool
    {
        return $user && $this->user_id === $user->id;
    }
}
