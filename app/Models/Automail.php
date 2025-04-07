<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Automail extends Model
{
    protected $fillable = [
        'name',
        'from',
        'cus_category',
        'days_from_last_invoice',
        'schedule_days',
        'schedule_time',
        'subject',
        'message',
        'attachment',
        'add_by',
        'last_executed_date'
    ];
}
