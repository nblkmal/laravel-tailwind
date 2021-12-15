<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'name',
        'email',
        'phone',
        'amount',
        'note',
        'payment_status',
        'toyyibPay_bill_code',
    ];

    public function setPhoneAttribute($value)
    {
        if(preg_match("/^([6])\d+/", $value))
        {
            $this->attributes['phone'] = $value;
        } elseif(preg_match("/^([0])\d+/", $value))
        {
            $this->attributes['phone'] = "6".$value;
        } else
        {
            $this->attributes['phone'] = "60".$value;
        }
    }
}
