<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EmployerDocument;

class Employer extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'industry',
        'business_type',
        'description',

        'office_address',
        'city',
        'barangay',

        'contact_person',
        'mobile_number',
        'email',

        'username',
        'password',

        'company_logo',
        'status',
    ];

    protected $hidden = [
        'password',
    ];

    protected $attributes = [
        'status' => 'Pending',
    ];

    public function documents()
    {
        return $this->hasOne(EmployerDocument::class, 'employer_id');
    }
}