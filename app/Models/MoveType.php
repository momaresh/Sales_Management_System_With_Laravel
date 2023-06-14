<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoveType extends Model
{
    use HasFactory;

    protected $table = 'move_types';
    protected $fillable =
    [
        'name', 'active', 'in_screen', 'is_private_internal'
    ];

    public $timestamps = false;
}
