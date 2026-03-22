<?php

namespace App\Models\Traits;

use App\Models\Archive;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait Archivable
{
    /**
     * Get the archive record for the model.
     */
    public function archive(): MorphOne
    {
        return $this->morphOne(Archive::class, 'archivable');
    }

    /**
     * Archive the model.
     */
    public function markAsArchived(): Archive
    {
        return $this->archive()->firstOrCreate();
    }

    /**
     * Unarchive the model.
     */
    public function unarchive(): bool
    {
        return $this->archive()->delete();
    }

    /**
     * Determine if the model is archived.
     */
    public function isArchived(): bool
    {
        return $this->archive()->exists();
    }

    /**
     * Scope a query to only include archived models.
     */
    public function scopeArchived(Builder $query): Builder
    {
        return $query->whereHas('archive');
    }

    /**
     * Scope a query to only include non-archived models.
     */
    public function scopeNotArchived(Builder $query): Builder
    {
        return $query->whereDoesntHave('archive');
    }
}
