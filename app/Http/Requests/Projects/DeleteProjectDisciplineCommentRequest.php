<?php

namespace App\Http\Requests\Projects;

use App\Enum\Permission;
use Illuminate\Foundation\Http\FormRequest;

class DeleteProjectDisciplineCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can(Permission::VIEW_PROJECT_DISCIPLINE_COMMENTS);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
