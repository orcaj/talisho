<?php

namespace App\Http\Requests;

use App\CSI;
use App\Documentation;
use Illuminate\Foundation\Http\FormRequest;

class AddAdditionalDocumentsToSubmittalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', [Documentation::class, $this->route('documentation')->entity->projectDiscipline]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'associated_document_ids' => 'present|array',
        ];
    }

    public function resolveAssociatedDocumentsForSubmittal()
    {
        return collect($this->validated()['associated_document_ids'])->map(function ($id) {
            return CSI::find($id);
        });
    }
}
