<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'suppliers';

    protected $fillable = ['person_id', 'supplier_code', 'com_code'];

    protected $primaryKey = null;
    public $incrementing = false;
}
