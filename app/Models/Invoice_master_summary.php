<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice_master_summary extends Model
{
    protected $connection="mysql_second";

    protected $table="invoice_master_summary";
}
