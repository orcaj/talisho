<?php

namespace App\Http\Requests;


class StoreDocumentationSubmissionRequest extends BaseFileUploadRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->route('documentation')->assigned_user_id === $this->user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge([
            'comments' => ['nullable', 'max:500'],
            'due_date' => ['required'],
        ], parent::rules());
    }
}
