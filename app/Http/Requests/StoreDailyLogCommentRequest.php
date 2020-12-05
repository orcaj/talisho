<?php

namespace App\Http\Requests;

use App\Enum\Permission;

class StoreDailyLogCommentRequest extends BaseFileUploadRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $projectDiscipline = $this->route('project_discipline');

        return $projectDiscipline->userBelongsToTeam($this->user())
            || $projectDiscipline->isLeadBy($this->user())
            || ($this->user()->can(Permission::VIEW_ALL_PROJECT_DISCIPLINES) && $this->user()->organization->id === $projectDiscipline->project->organization->id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge([
            'comment' => ['required', 'max:500']
        ], parent::rules());
    }
}
