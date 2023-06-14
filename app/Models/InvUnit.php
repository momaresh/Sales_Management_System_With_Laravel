<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvUnit extends Model
{
    use HasFactory;

    protected $table = 'inv_units';

    protected $fillable = ['name', 'active', 'master', 'added_by', 'updated_by', 'date', 'com_code'];

}
