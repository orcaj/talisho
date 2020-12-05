<?php

namespace App\Policies;

use App\ProjectDiscipline;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DocumentationPolicy
{
    use HandlesAuthorization;

    public function create(User $user, ProjectDiscipline $projectDiscipline)
    {
        if ($projectDiscipline->isLeadBy($user)) {
            return true;
        }

        return false;
    }
}
