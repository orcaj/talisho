<?php

namespace App\Http\Requests;


use App\Enum\DesignDocumentType;
use App\Enum\Permission;
use Illuminate\Validation\Rule;

class StoreDesignDocumentRequest extends BaseFileUploadRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return ($this->user()->can(Permission::CREATE_DESIGN_DOCUMENTS) && $this->route('organization')->employees->contains($this->user()))
            || $this->user()->leadsDisciplineInProject($this->route('project'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge([
            'name' => ['required', 'max:255', 'string'],
            'type' => ['required', Rule::in(DesignDocumentType::singular())]
        ], parent::rules());
    }
}
