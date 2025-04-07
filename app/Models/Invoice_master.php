<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice_master extends Model
{
    protected $connection="mysql_second";

    protected $table="invoice_master";
}
