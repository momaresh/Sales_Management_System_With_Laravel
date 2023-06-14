<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminTreasury extends Model
{
    use HasFactory;

    protected $primaryKey = null;
    public $incrementing = false;

    protected $table = 'admin_treasuries';

    protected $fillable = ['admin_id', 'treasuries_id', 'active', 'added_by', 'updated_by', 'com_code'];
}
