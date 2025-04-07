<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupList extends Model
{
    protected $fillable = [
        'group_name',
        'customer_id',
        'add_by'
    ];
}
