<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Employer;

class JobPosting extends Model
{
    use HasFactory;

    protected $table = 'job_postings';

    protected $fillable = [
        'title',
        'description',
        'country',
        'job_type',
        'salary',
        'employer_id',
        'employer_name',
        'employer_logo',
    ];

    public function employer()
    {
        return $this->belongsTo(Employer::class, 'employer_id');
    }

    public function logoUrl()
    {
        $logo = $this->employer_logo ?: optional($this->employer)->company_logo;

        if (! $logo) {
            return null;
        }

        return Str::startsWith($logo, ['http://', 'https://'])
            ? $logo
            : asset($logo);
    }
}
