<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceOrderDetail extends Model
{
    use HasFactory;
    protected $table = 'invoice_order_details';

    protected $fillable = [
        'invoice_order_id', 'item_code', 'unit_id', 'quantity', 'rejected_quantity', 'unit_price',
        'total_price', 'production_date', 'expire_date',
        'batch_id', 'store_id', 'added_by', 'updated_by', 'created_at',
        'updated_at', 'com_code'
    ];
}
