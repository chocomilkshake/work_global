<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\EmployerDocument;

class Employer extends Authenticatable
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
        'owner',
        'mobile_number',
        'email',

        'username',
        'password',

        'company_logo',
        'status',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
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

    public function missingDocuments()
    {
        $documents = $this->documents;

        $required = [
            'business_permit' => 'Business Permit',
            'dti_sec' => 'DTI/SEC Document',
            'bir_certificate' => 'BIR Certificate',
            'municipal_permit' => 'Municipal Permit',
            'valid_id' => 'Valid ID',
        ];

        if (! $documents) {
            return array_values($required);
        }

        return collect($required)
            ->filter(function ($label, $field) use ($documents) {
                return empty($documents->{$field});
            })
            ->values()
            ->all();
    }
}