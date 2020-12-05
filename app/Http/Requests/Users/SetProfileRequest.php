<?php

namespace App\Http\Requests\Users;

use App\Http\Requests\TalihoRequest;

class SetProfileRequest extends TalihoRequest
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
        $phoneRegexRule = 'regex:/^\d{3}-\d{3}-\d{4}$/';
        $zipRegexRule = 'regex:/^[0-9]{5}$/';
        return [
            'email' => 'required|unique:users,email,' . $this->route('user')->id,
            'password' => 'required|confirmed|min:6',
            'first_name' => 'required',
            'last_name' => 'required',
            'company_name' => 'required',
            'company_address_1' => 'required',
            'company_address_2' => 'nullable',
            'company_city' => 'required',
            'company_state' => 'required',
            'company_zip' => ['required', $zipRegexRule],
            'mobile_phone' => ['required', $phoneRegexRule],
        ];
    }

    public function messages()
    {
        $phoneRegexMessage = 'Phone number must match format XXX-XXX-XXXX';
        $zipRegexMessage = 'Zip Code must match format XXXXX';
        return [
            'mobile_phone.regex' => $phoneRegexMessage,
            'company_zip.regex' => $zipRegexMessage,
        ];
    }

    public function getCompanyName()
    {
        return $this->getValidated()['company_name'];
    }

    public function getCompanyAddress1()
    {
        return $this->getValidated()['company_address_1'];
    }

    public function getCompanyAddress2()
    {
        if (array_key_exists('company_address_2',  $this->getValidated())) {
            return $this->getValidated()['company_address_2'];
        }
        return '';
    }

    public function getCompanyCity()
    {
        return $this->getValidated()['company_city'];
    }

    public function getCompanyState()
    {
        return $this->getValidated()['company_state'];
    }

    public function getCompanyZip()
    {
        return $this->getValidated()['company_zip'];
    }
}
