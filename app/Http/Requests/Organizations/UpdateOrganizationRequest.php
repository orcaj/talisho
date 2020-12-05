<?php

namespace App\Http\Requests\Organizations;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrganizationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // TODO limit this to PMs
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
            'name' => [
                'required',
                'unique:organizations,name,' . $this->route('organization')->id
            ],
            'address_1' => 'required',
            'address_2' => 'nullable',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'phone' => ['required', 'regex:/\d{3}-\d{3}-\d{4}/'],
            'website' => 'nullable',
            'country' => 'required',
            'employee_count_id' => 'required',
            'primary_contact_name' => 'required',
            'primary_contact_email' => 'required',
            'primary_contact_phone' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'phone.regex' => 'Phone number must match format XXX-XXX-XXXX'
        ];
    }
}
