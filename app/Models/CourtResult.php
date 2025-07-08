<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourtResult extends Model
{
    protected $guarded = ['id'];

    public function courtSchedule(): BelongsTo
    {
        return $this->belongsTo(CourtSchedule::class);
    }
}
