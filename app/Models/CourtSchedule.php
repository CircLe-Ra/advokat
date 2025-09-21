<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourtSchedule extends Model
{
    protected $guarded = ['id'];

    public function legalCase(): BelongsTo
    {
        return $this->belongsTo(LegalCase::class);
    }

    public function CourtResult(): HasMany
    {
        return $this->hasMany(CourtResult::class);
    }

    public function CourtDocumentation(): HasMany
    {
        return $this->hasMany(CourtDocumentation::class);
    }
}
