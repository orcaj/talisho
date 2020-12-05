<?php

namespace App\Http\Requests\Projects;

use App\Http\Requests\TalihoRequest;

class SaveProjectRequest extends TalihoRequest
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
            'generalInfo.clientName' => ['required', 'max:255'],
            'generalInfo.projectName' => ['required', 'max:255'],
            'generalInfo.city' => ['required', 'max:255'],
            'generalInfo.street' => ['required', 'max:255'],
            'generalInfo.state' => ['required', 'max:255'],
            'generalInfo.zip' => ['required', 'max:255'],
            'generalInfo.description' => ['required'],
            'disciplineData.saveDefaults' => ['required', 'boolean'],
            'disciplineData.selected.*.id' => ['required', 'exists:disciplines'],
            'disciplineData.selected.*.lead.id' => ['required', 'exists:users'],
        ];
    }

    public function getClientName() {
        return $this->getValidated()['generalInfo']['clientName'];
    }

    public function getProjectName() {
        return $this->getValidated()['generalInfo']['projectName'];
    }

    public function getCity() {
        return $this->getValidated()['generalInfo']['city'];
    }

    public function getStreet() {
        return $this->getValidated()['generalInfo']['street'];
    }

    public function getState() {
        return $this->getValidated()['generalInfo']['state'];
    }

    public function getZip() {
        return $this->getValidated()['generalInfo']['zip'];
    }

    public function getDescription() {
        return $this->getValidated()['generalInfo']['description'];
    }

    public function getSaveDefaults() {
        return $this->getValidated()['disciplineData']['saveDefaults'];
    }

    public function getSelectedDisciplines() {
        return $this->getValidated()['disciplineData']['selected'];
    }
}
