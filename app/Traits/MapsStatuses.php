<?php


namespace App\Traits;

use App\Documentation;
use App\Enum\DocumentType;
use App\Enum\MessagingStatus;
use App\Enum\Permission;
use App\Enum\ProjectLevelStatuses;
use App\User;
use App\Rfi;

trait MapsStatuses
{
    protected function documentationStatusMap($disc, $user)
    {
        return [
            DocumentType::GENERAL_REQUIREMENT => $disc->generalRequirementsStatus($user),
            DocumentType::SUBMITTAL => $disc->submittalsStatus($user),
            DocumentType::TAB_CX_LEED => $disc->tabCxLeedStatus($user)
        ];
    }

    protected function rfiStatusMap(Rfi $rfi, $user)
    {
        if ($rfi->projectDiscipline->isLeadBy($user)
            || $user->can(Permission::VIEW_ALL_PROJECT_DISCIPLINES)) {
            return $this->leadRfiStatusMap($rfi);
        }

        return $this->guestRfiStatusMap($rfi);
    }

    protected function leadRfiStatusMap(Rfi $rfi)
    {
        return [
            MessagingStatus::APPROVED => ProjectLevelStatuses::GOOD_STANDING,
            MessagingStatus::UNDER_REVIEW => ProjectLevelStatuses::WARNING,
            MessagingStatus::LATE => ProjectLevelStatuses::ISSUE
        ][$rfi->status];
    }

    protected function guestRfiStatusMap(Rfi $rfi)
    {
        return [
            MessagingStatus::APPROVED => ProjectLevelStatuses::GOOD_STANDING,
            MessagingStatus::UNDER_REVIEW => ProjectLevelStatuses::NO_ACTION,
            MessagingStatus::LATE => ProjectLevelStatuses::NO_ACTION
        ][$rfi->status];
    }

    protected function documentMessagingStatus(Documentation $documentation, User $user)
    {
        if ($documentation->isDueInMoreThanSevenDays) {
            return ProjectLevelStatuses::NO_ACTION;
        }

        if ($this->dueDateUnassigned($documentation)) {
            return ProjectLevelStatuses::NO_ACTION;
        }

        if ($documentation->entity->projectDiscipline->isLeadBy($user)
            || $user->can(Permission::VIEW_ALL_PROJECT_DISCIPLINES)) {
            return $this->leadDocumentMessagingStatus($documentation);
        }

        return $this->guestDocumentMessagingStatus($documentation);
    }

    protected function leadDocumentMessagingStatus(Documentation $documentation)
    {
        if ($documentation->isOutstanding) {
            return ProjectLevelStatuses::NO_ACTION;
        }

        return [
            MessagingStatus::OUTSTANDING => ProjectLevelStatuses::NO_ACTION,
            MessagingStatus::APPROVED => ProjectLevelStatuses::GOOD_STANDING,
            MessagingStatus::UNDER_REVIEW => ProjectLevelStatuses::WARNING,
            MessagingStatus::LATE => ProjectLevelStatuses::ISSUE
        ][$documentation->messaging_status];
    }

    protected function guestDocumentMessagingStatus(Documentation $documentation)
    {
        if ($documentation->isUnderReview) {
            return ProjectLevelStatuses::NO_ACTION;
        }

        return [
            MessagingStatus::OUTSTANDING => ProjectLevelStatuses::WARNING,
            MessagingStatus::APPROVED => ProjectLevelStatuses::GOOD_STANDING,
            MessagingStatus::UNDER_REVIEW => ProjectLevelStatuses::NO_ACTION,
            MessagingStatus::LATE => ProjectLevelStatuses::ISSUE
        ][$documentation->messaging_status];
    }

    protected function dueDateUnassigned(Documentation $documentation)
    {
        return ! $documentation->isApproved && is_null($documentation->due_date);
    }
}
