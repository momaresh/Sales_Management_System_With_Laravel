<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers';

    protected $fillable = ['person_id', 'customer_code', 'com_code'];

    protected $primaryKey = null;
    public $incrementing = false;
}
