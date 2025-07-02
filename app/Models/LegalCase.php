<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LegalCase extends Model
{
    protected $guarded = ['id'];

    protected $with = ['documents', 'validations', 'client'];

    public function documents(): HasMany
    {
        return $this->hasMany(LegalCaseDocument::class);
    }

    public function validations(): HasMany
    {
        return $this->hasMany(LegalCaseValidation::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
