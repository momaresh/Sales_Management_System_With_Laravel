<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvItemCardMovementCategory extends Model
{
    use HasFactory;
    protected $table = 'inv_item_card_movements_categories';

    protected $fillable = [
        'id', 'name'
    ];
    public $timestamps = false;
}
