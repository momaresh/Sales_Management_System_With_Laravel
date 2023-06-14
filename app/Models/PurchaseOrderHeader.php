<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderHeader extends Model
{
    use HasFactory;
    protected $table = 'purchase_order_header';

    protected $fillable = [
        'invoice_id', 'auto_serial', 'purchase_code',
        'supplier_code', 'store_id', 'added_by', 'created_at',
        'updated_at', 'updated_by', 'com_code'
    ];

}
