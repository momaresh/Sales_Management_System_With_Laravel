<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $table = 'stores';

    protected $fillable = ['store_code', 'name', 'active', 'phone', 'address', 'added_by', 'updated_by', 'date', 'com_code'];

}
