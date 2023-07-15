<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OriginalReturnInvoice extends Model
{
    use HasFactory;
    protected $table = 'original_return_invoice';

    protected $fillable = [
        'invoice_order_id', 'pill_code', 'total_cost', 'pill_type', 'what_paid',
        'what_remain', 'money_for_account',  'return_date',
        'added_by', 'updated_by', 'created_at', 'updated_at', 'com_code'
    ];


    public $incrementing = false;
}
