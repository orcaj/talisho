<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeCount extends Model
{
    protected $fillable = [
        'label',
        'sort'
    ];
}
