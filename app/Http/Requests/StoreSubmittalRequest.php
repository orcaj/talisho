<?php

namespace App\Http\Requests;

use App\Contracts\Specification;
use App\CSI;
use App\CustomSpecification;
use App\Documentation;
use App\Enum\DocumentType;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class StoreSubmittalRequest extends TalihoRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', [Documentation::class, $this->route('project_discipline')]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'submittal' => 'required',
            'submittal.name' => 'required',
            'submittal.code' => 'required',
            'associated_document_ids' => 'present|array',
            'due_date' => 'nullable'
        ];
    }

    public function resolveSpecificationForSubmittal(): Specification
    {
        try {

            return CSI::where('code', $this->validated()['submittal']['code'])
                ->where('name', $this->validated()['submittal']['name'])
                ->firstOrFail();

        } catch (ModelNotFoundException $exception) {

            return CustomSpecification::firstOrCreate([
                'name' => $this->validated()['submittal']['name'],
                'code' => $this->validated()['submittal']['code'],
                'type' => DocumentType::SUBMITTAL,
                'organization_id' => $this->route('project_discipline')->project->organization->id,
            ]);

        }
    }

    public function resolveAssociatedDocumentsForSubmittal()
    {
        return collect($this->validated()['associated_document_ids'])->map(function ($id) {
            return CSI::find($id);
        });
    }
}
