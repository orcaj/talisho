<?php

namespace App\Http\Requests;

use App\CSI;
use App\CustomSpecification;
use App\Documentation;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;

class ImportOtherDocumentRequest extends FormRequest
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
            'old_project_id' => 'required',
        ];
    }

}
