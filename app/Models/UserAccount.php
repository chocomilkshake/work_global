<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAccount extends Model
{
    protected $table = 'user_account';

    public $timestamps = false;

    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'role',
        'created_date',
        'deleted_at'
    ];
}