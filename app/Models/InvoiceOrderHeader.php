<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceOrderHeader extends Model
{
    use HasFactory;
    protected $table = 'invoice_order_header';

    protected $fillable = [
        'id', 'pill_code', 'order_type', 'pill_number', 'order_date', 'discount_type',
        'discount_percent', 'discount_value', 'tax_percent',
        'total_before_discount', 'total_cost', 'pill_type', 'what_paid',
        'what_remain', 'money_for_account','notes',
        'invoice_type', 'is_original_return', 'is_approved', 'approved_by', 'approved_at',
        'added_by', 'updated_by', 'created_at', 'updated_at', 'com_code'
    ];


    public $incrementing = false;
    public $primaryKey = null;
}
