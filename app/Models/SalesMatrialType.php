<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesMatrialType extends Model
{
    use HasFactory;

    protected $table = 'sales_matrial_type';

    protected $fillable = ['name', 'active', 'added_by', 'updated_by', 'date', 'com_code'];
  
}
