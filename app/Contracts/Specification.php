<?php


namespace App\Contracts;


use Illuminate\Database\Eloquent\Relations\MorphMany;

interface Specification
{
    public function submittals(): MorphMany;

    public function documents(): MorphMany;
}
