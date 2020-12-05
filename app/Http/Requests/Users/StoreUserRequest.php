<?php

namespace App\Http\Requests\Users;

use App\User;
use App\ProjectDiscipline;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'email' => 'required|email',
            'first_name' => 'required',
            'last_name' => 'required',
            'discipline' => ''
        ];
    }

    /**
     * @param $validator
     *
     * Enforce unique email on users if user exists and is registered for requested email,
     * or user with requested email belongs to different Organization than the authenticated Project Manager.
     * Otherwise, we want to ignore the unique email so we can resend the invite for the un-registered user.
     *
     */
    public function withValidator($validator)
    {
        $validator->sometimes('email', 'unique:users', function ($data) {
            $user = User::whereEmail($data->get('email'))->first();
            if ($user && $data->get('discipline')){
                $projectDiscipline = ProjectDiscipline::where('id', $data->get('discipline')) -> first();
                return $user && (in_array($user->id, $projectDiscipline -> team()->pluck('users.id')->toArray()));
            };
            $user = User::whereEmail($data->get('email'))->first();
            return $user ;
        });
    }

    public function data()
    {
        return array_merge($this->validated(), [
            'organization_id' => $this->user()->organization->id
        ]);
    }
}
