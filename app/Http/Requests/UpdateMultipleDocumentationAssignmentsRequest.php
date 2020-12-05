<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMultipleDocumentationAssignmentsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'assigned_user_id' => ['required', 'exists:users,id'],
            'documentation_ids' => ['required', 'array'],
            'documentation_ids.*' => ['exists:documentations,id']
        ];
    }
}
