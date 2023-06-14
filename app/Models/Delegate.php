<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delegate extends Model
{
    use HasFactory;

    protected $table = 'delegates';

    protected $fillable = ['person_id', 'delegate_code', 'percent_type',
    'percent_sales_commission_group', 'percent_sales_commission_half_group',
    'percent_sales_commission_one', 'com_code'];

    protected $primaryKey = null;
    public $incrementing = false;
}
