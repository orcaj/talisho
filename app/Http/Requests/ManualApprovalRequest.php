<?php

namespace App\Http\Requests;

use App\Enum\Permission;

class ManualApprovalRequest extends BaseFileUploadRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->route('documentation')->lead_user_id === $this->user()->id || $this->user()->can(Permission::AUTO_APPROVE_DOCUMENTS);
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
            'files' => ['required']
        ], parent::rules());
    }
}
