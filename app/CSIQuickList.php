<?php

namespace App;

use App\Enum\QuickListType;
use Illuminate\Database\Eloquent\Model;

class CSIQuickList extends Model
{
    protected $table = 'csi_quick_lists';

    protected $guarded = [];

    public function scopeAssociatedDocuments($query)
    {
        return $query->whereQuickList(QuickListType::ASSOCIATED_DOCUMENTS);
    }

    public function scopeGeneralRequirements($query)
    {
        return $query->whereQuickList(QuickListType::GENERAL_REQUIREMENTS);
    }

    public function scopeTab($query)
    {
        return $query->whereQuickList(QuickListType::TAB);
    }

    public function csi()
    {
        return $this->belongsTo(CSI::class, 'csi_id');
    }
}
