<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentInfo extends Model
{
    protected $fillable=[
        'user_id', 'sub_id', 'customer_id', 'status'
    ];
}
