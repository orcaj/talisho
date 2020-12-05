<?php


namespace App\Services;


use App\Contracts\Specification;
use App\Documentation;
use App\Enum\DocumentType;
use App\Enum\MessagingStatus;
use App\Http\Data\DocumentationData;
use App\OtherDocument;
use App\Submittal;

class CreateDocumentationService
{
    public function createSubmittal(DocumentationData $documentationData)
    {
        $count = $documentationData->getProjectDiscipline()->submittals->count();

        $submittal = Submittal::create([
            'specification_id' => $documentationData->getSubmittalSpecification()->id,
            'specification_type' => get_class($documentationData->getSubmittalSpecification()),
            'project_discipline_id' => $documentationData->getProjectDiscipline()->id,
        ]);

        $identifier = $this->createIdentifier($documentationData,  $count + 1);

        $this->associateDocumentation($documentationData, $submittal, Submittal::class, $identifier);

        $this->createOtherDocuments($documentationData, DocumentType::SUBMITTAL , $submittal);
    }

    public function createTabDocuments(DocumentationData $documentationData)
    {
        $this->createOtherDocuments($documentationData, DocumentType::TAB_CX_LEED);
    }

    public function createGeneralRequirementsDocuments(DocumentationData $documentationData)
    {
        $this->createOtherDocuments($documentationData, DocumentType::GENERAL_REQUIREMENT);
    }

    public function createOtherDocuments(DocumentationData $documentationData, $type, Submittal $submittal = null)
    {
        $count = $documentationData->getProjectDiscipline()->otherDocuments->where('document_type', $type)->count();

        collect($documentationData->getOtherDocumentSpecifications())->each(function (Specification $specification) use ($documentationData, $submittal, $type, &$count) {
            // the count for the identifier should always be the next available incrementing number for this project discipline
            $count = $count + 1;

            $doc = OtherDocument::create([
                'specification_id' => $specification->id,
                'specification_type' => get_class($specification),
                'project_discipline_id' => $documentationData->getProjectDiscipline()->id,
                'submittal_id' => $submittal ? $submittal->id : null,
                'document_type' => $type,
            ]);

            $identifier = $this->createIdentifier($documentationData, $count);

            $this->associateDocumentation($documentationData, $doc, OtherDocument::class, $identifier);
        });
    }

    protected function associateDocumentation(DocumentationData $documentationData, $createdDoc, $type, $identifier)
    {
        Documentation::create([
            'assigned_user_id' => $documentationData->getAssigned()->id,
            'lead_user_id' => $documentationData->getLead()->id,
            'entity_id' => $createdDoc->id,
            'entity_type' => $type,
            'identifier' => $identifier,
            'due_date' => $documentationData->getDueDate(),
            'status' => MessagingStatus::OUTSTANDING
        ]);
    }

    protected function createIdentifier(DocumentationData $documentationData, $count)
    {
        $abbr = $documentationData->getProjectDiscipline()->discipline->abbreviation;

        return $abbr . str_pad($count, 3, '0', STR_PAD_LEFT);
    }
}
