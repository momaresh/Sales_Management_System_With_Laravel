<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvItemCard extends Model
{
    use HasFactory;

    protected $table = 'inv_item_card';

    protected $fillable = [
        'item_code',
         'barcode',
          'name',
           'item_type',
            'inv_itemcard_categories_id',
             'parent_inv_itemcard_id',
              'does_has_retailunit',
               'retail_unit_id',
                'unit_id',
                 'retail_uom_quntToParent',
                    'has_fixed_price',
                    'all_quantity_with_retail_unit',
                    'all_quantity_with_master_unit',
                    'remain_quantity_in_retail',
                    'item_img',
                    'active',
                        'added_by',
                        'updated_by',
                        'date',
                        'com_code'];
}
