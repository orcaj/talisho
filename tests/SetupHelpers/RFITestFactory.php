<?php


namespace Tests\SetupHelpers;


use App\ProjectDiscipline;
use App\Rfi;
use App\RfiResponse;
use App\User;

class RFITestFactory
{
    protected $dueDate = null;
    protected $discipline = null;
    protected $createdBy = null;
    protected $rfi = null;
    protected $response = null;

    public function createUnderReview()
    {
        return $this->createRfi();
    }

    public function createLate()
    {
        $this->dueDate = now()->subDays(2);

        return $this->createRfi();
    }

    public function createApproved()
    {
        return $this->createRfiResponse();
    }

    public function createdBy(User $user)
    {
        $this->createdBy = $user;

        return $this;
    }

    public function forDiscipline(ProjectDiscipline $discipline)
    {
        $this->discipline = $discipline;

        return $this;
    }

    public function dueAt($dueDate)
    {
        $this->dueDate = $dueDate;

        return $this;
    }

    protected function createRfi()
    {
        return $this->rfi = factory(Rfi::class)->create([
            'project_discipline_id' => $this->discipline ?? $this->discipline = factory(\App\ProjectDiscipline::class),
            'guest_user_id' => $this->createdBy ?? $this->createdBy = factory(User::class),
            'created_by_user_id' => $this->createdBy,
            'due_date' => $this->dueDate ?? now()->addWeeks(2),
        ]);
    }

    protected function createRfiResponse()
    {
        $this->response = factory(RfiResponse::class)->create([
            'rfi_id' => $this->rfi ?? $this->createRfi(),
            'from_lead_user_id' => $this->discipline->lead,
        ]);

        return $this->rfi;
    }
}
