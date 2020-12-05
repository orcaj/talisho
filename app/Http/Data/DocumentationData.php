<?php


namespace App\Http\Data;


use App\Contracts\Specification;
use App\ProjectDiscipline;
use App\User;
use Illuminate\Support\Collection;

class DocumentationData
{
    protected $otherDocumentSpecifications;
    protected $projectDiscipline;
    protected $lead;
    protected $assigned;
    protected $submittalSpecification;
    protected $dueDate;

    public function __construct(ProjectDiscipline $projectDiscipline, User $lead, User $assigned, $dueDate, Collection $otherDocumentSpecifications = null, Specification $submittalSpecification = null)
    {
        $this->projectDiscipline = $projectDiscipline;
        $this->lead = $lead;
        $this->assigned = $assigned;
        $this->otherDocumentSpecifications = $otherDocumentSpecifications;
        $this->submittalSpecification = $submittalSpecification;
        $this->dueDate = $dueDate;
    }

    public function getOtherDocumentSpecifications(): Collection
    {
        return $this->otherDocumentSpecifications ?? collect([]);
    }

    public function getProjectDiscipline(): ProjectDiscipline
    {
        return $this->projectDiscipline;
    }

    public function getLead(): User
    {
        return $this->lead;
    }

    public function getAssigned(): User
    {
        return $this->assigned;
    }

    public function getSubmittalSpecification(): Specification
    {
        return $this->submittalSpecification;
    }

    public function getDueDate()
    {
        return $this->dueDate;
    }
}
