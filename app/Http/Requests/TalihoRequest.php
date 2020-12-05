<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TalihoRequest extends FormRequest
{
    protected $validatedData;

    protected function getValidated() {
        return $this->validatedData ?? $this->validatedData = $this->validated();
    }
}
