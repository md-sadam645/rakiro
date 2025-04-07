<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailCompose extends Model
{
    protected $fillable = [
        'from',
        'to',
        'to_group_name',
        'cc',
        'subject',
        'message',
        'attachment',
        'schedule_time',
        'add_by'
    ];
}
