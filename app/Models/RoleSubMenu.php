<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleSubMenu extends Model
{
    use HasFactory;

    protected $table = 'roles_sub_menu';

    protected $fillable = ['roles_main_menu_id', 'name', 'active', 'added_by', 'updated_by', 'com_code'];
}
