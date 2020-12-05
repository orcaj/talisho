<?php

namespace App\Http\Requests\Projects;


use App\Http\Requests\TalihoRequest;

class SaveProjectDisciplineCommentRequest extends TalihoRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->id === $this->route('project_discipline')->lead->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'comment' => 'max:300'
        ];
    }

    public function getComment()
    {
        return $this->getValidated()['comment'];
    }
}
