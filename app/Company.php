<?php

namespace App;

use App\Enum\Role;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    // TODO switch to guarded and unguard any other models
    protected $fillable = [
        'name',
        'address_1',
        'address_2',
        'city',
        'state',
        'zip'
    ];

}
