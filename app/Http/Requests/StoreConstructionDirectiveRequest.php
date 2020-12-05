<?php

namespace App\Http\Requests;


class StoreConstructionDirectiveRequest extends BaseFileUploadRequest
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
        return array_merge([
            'subject' => ['required', 'max:255'],
            'drawing_number' => ['nullable', 'max:255'],
            'specification_number' => ['nullable', 'max:255'],
            'directive' => ['required', 'max:500'],
            'guests' => ['required', 'array'],
            'guests.*' => ['exists:users,id']
        ], parent::rules());
    }
}
