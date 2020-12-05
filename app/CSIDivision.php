<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CSIDivision extends Model
{
    protected $guarded = [];

    protected $table = 'csi_divisions';

    const GENERAL_REQUIREMENT_CODE_RANGE = ['000000', '010000'];
    const SUBMITTAL_CODE_RANGE = ['020000', '480000'];

    public function scopeGeneralRequirements($query)
    {
        return $query->whereIn('code', self::GENERAL_REQUIREMENT_CODE_RANGE);
    }

    public function scopeSubmittals($query)
    {
        return $query->whereBetween('code', self::SUBMITTAL_CODE_RANGE);
    }
}
