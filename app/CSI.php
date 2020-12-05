<?php

namespace App;

use App\Contracts\Specification;
use App\Enum\DocumentType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class CSI extends Model implements Specification
{
    protected $guarded = [];

    protected $table = 'csis';

    public function getRouteKeyName()
    {
        return 'code';
    }

    public function division()
    {
        return $this->belongsTo(CSIDivision::class, 'csi_division_id');
    }

    public function documents(): MorphMany
    {
        return $this->morphMany(OtherDocument::class, 'specification');
    }

    public function submittals(): MorphMany
    {
        return $this->morphMany(Submittal::class, 'specification');
    }

    public function scopeSearchByNameOrCode($query, $search)
    {
        return $query->where(function ($query) use ($search) {
            $query->where('code', 'LIKE', $search . '%')
                ->orWhere('name', 'LIKE', '%' . $search . '%');
        });
    }

    public function getDocumentsForProject(Project $project, bool $isForSubmittalsOrCommissioning)
    {
        // this is just for associated documents right now
        $otherDocuments = $this->documents()
            ->whereIn('project_discipline_id', $project->disciplines()->pluck('id'))
            ->when($isForSubmittalsOrCommissioning, function($query) {
                $query->where('document_type', DocumentType::SUBMITTAL);
            })
            ->whereHas('documentation', function(Builder $query) {
                $query->whereNull('deleted_at');
            })
            ->with(['documentation.submissions.response'])
            ->when($isForSubmittalsOrCommissioning, function ($query) {
                $query->with(['submittal.specification']);
            })
            ->get();

        return $otherDocuments->map(function($document) use ($isForSubmittalsOrCommissioning) {
            return [
                'project_discipline_id' => $document->project_discipline_id,
                'submittal' => $isForSubmittalsOrCommissioning ? $document->submittal : null,
                'approved' => $document->documentation->isApproved,
                'isForSubmittalOrCommissioning' =>  $isForSubmittalsOrCommissioning,
                'file' => $document->documentation->isApproved ? $document->documentation->responses->last()->files->first() : null
            ];
        });
    }
}
