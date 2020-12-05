<?php

namespace App;

use App\Contracts\Specification;
use App\Enum\DocumentType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class CustomSpecification extends Model implements Specification
{
    protected $guarded = [];

    public function submittals(): MorphMany
    {
        return $this->morphMany(Submittal::class, 'specification');
    }

    public function documents(): MorphMany
    {
        return $this->morphMany(OtherDocument::class, 'specification');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function scopeGeneralRequirements($query)
    {
        return $query->whereType(DocumentType::GENERAL_REQUIREMENT);
    }

    public function scopeSubmittals($query)
    {
        return $query->whereType(DocumentType::SUBMITTAL);
    }

    public function scopeTabCxLeed($query)
    {
        return $query->wheretype(DocumentType::TAB_CX_LEED);
    }
}
