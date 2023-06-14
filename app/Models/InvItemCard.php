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
                  'price_per_one_in_master_unit',
                   'price_per_half_group_in_master_unit',
                    'price_per_group_in_master_unit',
                     'price_per_one_in_retail_unit',
                      'price_per_half_group_in_retail_unit',
                       'price_per_group_in_retail_unit',
                        'cost_price_in_master',
                         'cost_price_in_retail',
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
