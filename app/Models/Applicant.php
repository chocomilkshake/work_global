<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    protected $fillable = [

        'first_name',
        'middle_name',
        'last_name',

        'street_no',
        'full_address',
        'region',
        'city',
        'barangay',

        'contact_number',
        'email',

        // NEW
        'username',
        'password',

        'profile_image',
        'resume',
    ];

    protected $hidden = [
        'password'
    ];
}