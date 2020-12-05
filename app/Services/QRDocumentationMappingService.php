<?php


namespace App\Services;


use App\CSI;
use App\Enum\DocumentType;
use App\Project;
use Illuminate\Database\Eloquent\Builder;

class QRDocumentationMappingService
{
    public function mapSubmittalsForQr(Project $project)
    {
        $submittals = $project->submittals();

        return $this->mapGeneric($submittals);
    }

    public function mapCommissioningDocumentsForQr(Project $project)
    {
        $tabAndLeedSpecificCsiIds = collect([
            CSI::whereCode('230593')->first(),
            CSI::whereCode('017853')->first()
        ])->pluck('id');

        $documents = $project->otherDocuments()
            ->where('document_type', DocumentType::TAB_CX_LEED)
            ->where('specification_type', CSI::class)
            ->whereNotIn('specification_id', $tabAndLeedSpecificCsiIds);

        return $this->mapGeneric($documents);
    }

    // this name sucks, think of a better name
    protected function mapGeneric($documents)
    {
        $documentation = $documents
            ->whereHas('documentation', function (Builder $query) {
                $query->whereNull('deleted_at');
            })
            ->with(['documentation.submissions.response', 'specification'])
            ->get();


        return $documentation->map(function ($doc) {
            return [
                'specification' => $doc->specification,
                'project_discipline_id' => $doc->project_discipline_id,
                'approved' => $doc->documentation->isApproved,
                'isForSubmittalOrCommissioning' => true,
                'file' => $doc->documentation->isApproved ? $doc->documentation->responses->last()->files->first() : null
            ];
        });
    }

}
