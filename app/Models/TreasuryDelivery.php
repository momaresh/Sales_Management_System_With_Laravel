<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreasuryDelivery extends Model
{
    use HasFactory;

    protected $primaryKey = null;
    public $incrementing = false;

    protected $table = 'treasuries_delivery';

    protected $fillable = ['treasuries_id', 'treasuries_receive_from_id', 'added_by', 'updated_by', 'com_code'];

}
