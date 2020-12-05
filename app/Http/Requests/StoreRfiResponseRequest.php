<?php

namespace App\Http\Requests;


class StoreRfiResponseRequest extends BaseFileUploadRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->route('project_discipline')->isLeadBy($this->user());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge([
            'response' => ['required', 'max:500'],
        ], parent::rules());
    }
}
