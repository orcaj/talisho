<?php


namespace App\Services;


use App\Contracts\Specification;
use App\Documentation;
use App\Enum\DocumentType;
use App\Enum\MessagingStatus;
use App\Http\Data\DocumentationData;
use App\OtherDocument;
use App\Project;
use App\ProjectDiscipline;
use App\Submittal;

class ImportDocumentationService
{
    public function importSubmittal($projectDiscipline, $old_project_id)
    {
        $result  =  $this->importOtherDocuments($projectDiscipline, $old_project_id, DocumentType::SUBMITTAL);
        return $result;
    }

    public function importTabDocuments($projectDiscipline, $old_project_id)
    {
        $result  =  $this->importOtherDocuments($projectDiscipline, $old_project_id, DocumentType::TAB_CX_LEED);
        return $result;
    }

    public function importGeneralRequirementsDocuments($projectDiscipline, $old_project_id)
    {
        $result  =  $this->importOtherDocuments($projectDiscipline, $old_project_id, DocumentType::GENERAL_REQUIREMENT);
        return $result;
    }

    public function importOtherDocuments($projectDiscipline, $old_project_id, $type)
    {

        $count = $projectDiscipline->otherDocuments->where('document_type', $type)->count();

        $oldProjectDisciplines = Project::where('id', $old_project_id)->first()->disciplines;
        $matchedDiscipline = null;

        foreach ($oldProjectDisciplines as $discipline ) {
            if ($discipline ->discipline_id === $projectDiscipline -> discipline_id)
                $matchedDiscipline = $discipline;
        }

        if (is_null($matchedDiscipline)) {
            return [
                "status" => false,
                "message" => "Failed to copy as a template, Because there isn't a matched discipline."
            ];
        }

        $documents = $matchedDiscipline -> otherDocuments->where('document_type', $type);

        error_log($type);
        error_log($documents->count());

        if ($documents->count() == 0 ) {
            return [
                "status" => false,
                "message" => "Failed to copy as a template, Becuase there isn't a document."
            ];
        }

        foreach ($documents as $document ) {
            $count = $count + 1;

            if ($type == DocumentType::SUBMITTAL){

                $submittal = Submittal::create([
                    'specification_id' =>  $document->specification_id,
                    'specification_type' => $document->specification_type,
                    'project_discipline_id' => $projectDiscipline->id,
                ]);
                $identifier = $this->createIdentifier($projectDiscipline, $count);

                $this->associateDocumentation($projectDiscipline, $submittal, Submittal::class, $identifier);
            }
            else{
                $submittal = null;
            }
            $doc = OtherDocument::create([
                'specification_id' => $document->specification_id,
                'specification_type' => $document->specification_type,
                'project_discipline_id' => $projectDiscipline->id,
                'submittal_id' => $submittal ? $submittal->id : null,
                'document_type' => $type,
            ]);

            $identifier = $this->createIdentifier($projectDiscipline, $count);

            $this->associateDocumentation($projectDiscipline, $doc, OtherDocument::class, $identifier);
        }

        return [
            "status" => true
        ];

    }

    protected function associateDocumentation(ProjectDiscipline $projectDiscipline, $createdDoc, $type, $identifier)
    {
        Documentation::create([
            'assigned_user_id' => $projectDiscipline->lead->id,
            'lead_user_id' =>  $projectDiscipline->lead->id,
            'entity_id' => $createdDoc->id,
            'entity_type' => $type,
            'identifier' => $identifier,
            'due_date' => null,
            'status' => MessagingStatus::OUTSTANDING
        ]);
    }

    protected function createIdentifier(ProjectDiscipline $projectDiscipline, $count)
    {
        $abbr = $projectDiscipline->discipline->abbreviation;

        return $abbr . str_pad($count, 3, '0', STR_PAD_LEFT);
    }
}
