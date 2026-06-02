<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployerDocument extends Model
{
    use HasFactory;

    protected $table = 'employer_documents';
    public $timestamps = false;

    protected $fillable = [
        'employer_id',
        'business_permit',
        'dti_sec',
        'bir_certificate',
        'municipal_permit',
        'valid_id',
        'created_at',
    ];
}
