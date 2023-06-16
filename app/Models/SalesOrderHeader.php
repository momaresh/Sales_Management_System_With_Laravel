<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrderHeader extends Model
{
    use HasFactory;
    protected $table = 'sales_order_header';

    protected $fillable = [
        'invoice_id', 'auto_serial',
        'customer_code', 'delegate_code',
        'sales_code', 'sales_type', 'delegate_commission_type',
        'delegate_commission', 'money_for_delegate','added_by', 'created_at',
        'updated_at', 'updated_by', 'com_code'
    ];

    public $incrementing = false;
    public $primaryKey = null;

}
