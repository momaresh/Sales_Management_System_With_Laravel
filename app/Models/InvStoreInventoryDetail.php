<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvStoreInventoryDetail extends Model
{
    use HasFactory;
    protected $table = 'inv_stores_inventory_details';
    protected $fillable =
    [
        'inv_stores_inventory_header_id', 'item_code', 'batch_id',
        'old_quantity', 'new_quantity', 'different_quantity', 'notes', 'is_closed',
        'created_at', 'updated_at', 'closed_at', 'added_by', 'updated_by', 'closed_by',
        'com_code'
    ];
}
