<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MeetingSchedule extends Model
{
    protected $guarded = ['id'];

    public function legalCase(): BelongsTo
    {
        return $this->belongsTo(LegalCase::class);
    }

    public function meetingDocumentations(): HasMany
    {
        return $this->hasMany(MeetingDocumentation::class);
    }

    public function meetingFileAdditions(): HasMany
    {
        return $this->hasMany(MeetingFileAddition::class);
    }

}
