<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    protected $table = 'person';

    protected $fillable = ['id', 'account_number', 'first_name', 'last_name', 'city_id', 'address', 'phone', 'person_type', 'active', 'added_by', 'updated_by', 'com_code'];
    protected $primaryKey = null;
    public $incrementing = false;
}
