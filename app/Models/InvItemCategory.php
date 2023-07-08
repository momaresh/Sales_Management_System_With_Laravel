<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvItemCategory extends Model
{
    use HasFactory;

    protected $table = 'inv_item_categories';

    protected $fillable = ['name', 'category_code', 'active', 'added_by', 'updated_by', 'date', 'com_code'];

}
