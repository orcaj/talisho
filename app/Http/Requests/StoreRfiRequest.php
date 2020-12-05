<?php

namespace App\Http\Requests;


class StoreRfiRequest extends BaseFileUploadRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->route()->parameter('project_discipline')->userBelongsToTeam($this->user());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge([
            'subject' => ['required', 'max:255'],
            'drawing_number' => ['nullable', 'max:255'],
            'specification_number' => ['nullable', 'max:255'],
            'due_date' => ['required', 'date'],
            'question' => ['required', 'max:500']
        ], parent::rules());
    }
}
