<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmtpSetup extends Model
{
    protected $fillable = [
        "name",
        "from_address",
        "add_by",
    ];
}
