<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvStoreInventoryHeader extends Model
{
    use HasFactory;
    protected $table = 'inv_stores_inventory_header';
    protected $fillable =
    [
        'store_id', 'inventory_date', 'inventory_type', 'total_cost_batches',
        'notes', 'is_closed', 'created_at', 'updated_at', 'closed_at',
        'added_by', 'updated_by', 'closed_by', 'date', 'com_code'
    ];
}
