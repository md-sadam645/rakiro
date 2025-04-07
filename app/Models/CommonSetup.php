<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommonSetup extends Model
{
    protected $fillable = [
        'mailer',
        'host',
        'port',
        "username",
        "password",
        "encryption",
        'add_by'
    ];
}
