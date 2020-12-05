<?php

namespace App\Http\Requests;

use App\Enum\DocumentationResponseStatus;

class StoreDocumentationSubmissionResponseRequest extends BaseFileUploadRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->route('documentation_submission')->documentation->lead_user_id === $this->user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge([
            'comments' => ['required', 'max:500'],
            'status' => ['required']
        ], parent::rules());
    }

    public function withValidator($validator)
    {
        // If document is approved a file is required
        // If document is rejected a new due date is required
        $validator->sometimes('files', 'required', function ($data) {
            return DocumentationResponseStatus::collection()->firstWhere('label', $data->get('status'))['approves'];
        });

        $validator->sometimes('due_date', 'required', function ($data) {
            return ! DocumentationResponseStatus::collection()->firstWhere('label', $data->get('status'))['approves'];
        });
    }
}
