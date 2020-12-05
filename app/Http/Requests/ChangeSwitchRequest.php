<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangeSwitchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->route('project_discipline')->project) || $this->user()->id === $this->route('project_discipline')->lead->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'documentation_status' => ['required'],
            'communication_status' => ['required'],
            'liability_status' => ['required']
        ];
    }
}
