<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvItemCardBatch extends Model
{
    use HasFactory;

    protected $table = 'inv_item_card_batches';

    protected $fillable = [
        'batch_code', 'store_id', 'item_code', 'inv_unit_id',
        'unit_cost_price', 'quantity', 'total_cost_price',
        'production_date', 'expire_date', 'added_by',
        'updated_by', 'com_code'
    ];
}
