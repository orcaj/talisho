<?php

namespace App\Http\Requests;


class StoreDailyLogRequest extends BaseFileUploadRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->route('project_discipline')->userBelongsToTeam($this->user());
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge([
            'log_date' => ['required', 'date'],
            'information' => ['required']
        ], parent::rules());
    }
}
