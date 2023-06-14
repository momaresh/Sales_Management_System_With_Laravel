<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvItemCardMovement extends Model
{
    use HasFactory;

    protected $table = 'inv_item_card_movements';

    protected $fillable = [
        'inv_item_card_movements_categories_id', 'item_code',
         'inv_item_card_movements_types_id', 'order_header_id',
          'order_details_id', 'store_id', 'batch_id', 'quantity_before_movement', 'quantity_before_movement_in_current_store',
          'quantity_after_movement_in_current_store', 'quantity_after_movement', 'byan',
            'added_by', 'created_at', 'updated_at', 'date', 'com_code'
        ];

}
