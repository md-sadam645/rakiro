<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order_summary extends Model
{
    protected $connection="mysql_second";

    protected $table="order_summary";
}
