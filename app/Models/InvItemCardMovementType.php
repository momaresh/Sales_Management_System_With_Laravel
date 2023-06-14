<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvItemCardMovementType extends Model
{
    use HasFactory;
    protected $table = 'inv_item_card_movements_types';

    protected $fillable = [
        'id', 'type'
    ];
    public $timestamps = false;
}
