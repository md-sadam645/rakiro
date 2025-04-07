<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item_master extends Model
{
    protected $connection="mysql_second";

    protected $table="item_master";
}
