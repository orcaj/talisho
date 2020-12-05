<?php

namespace App\Http\Requests;

use App\CSI;
use App\CustomSpecification;
use App\Documentation;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;

class StoreOtherDocumentRequest extends FormRequest
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
            'specifications' => 'required|array|min:1',
            'specifications.*.code' => 'required',
            'specifications.*.name' => 'required',
            'due_date' => 'nullable'
        ];
    }

    public function resolveSpecifications($type): Collection
    {
        return collect($this->validated()['specifications'])->map(function ($specification) use ($type) {
            try {
                return CSI::where('code', $specification['code'])
                    ->where('name', $specification['name'])
                    ->firstOrFail();
            } catch (ModelNotFoundException $exception) {
                return CustomSpecification::firstOrCreate([
                    'name' => $specification['name'],
                    'code' => $specification['code'],
                    'type' => $type,
                    'organization_id' => $this->route('project_discipline')->project->organization->id,
                ]);
            }
        });
    }
}
