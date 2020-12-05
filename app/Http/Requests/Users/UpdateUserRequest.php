<?php

namespace App\Http\Requests\Users;

use App\Rules\ConfirmOldPassword;

class UpdateUserRequest extends SetProfileRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->route('user')->id  === $this->user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // TODO Add extra validation regarding password strength requirements
        return array_merge(parent::rules(), [
            'old_password' => ['sometimes', 'required_with:password', new ConfirmOldPassword($this->route()->parameter('user')->password)],
            'password' => 'sometimes|confirmed|min:6',
        ]);
    }
}
