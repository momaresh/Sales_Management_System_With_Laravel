<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Treasury extends Model
{
    use HasFactory;

    protected $table = 'treasuries';

    protected $fillable = ['name', 'master', 'active', 'last_exchange_arrive', 'last_collection_arrive', 'added_by', 'updated_by', 'date', 'com_code'];

    
}
