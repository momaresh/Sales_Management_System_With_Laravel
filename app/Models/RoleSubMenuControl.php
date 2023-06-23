<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleSubMenuControl extends Model
{
    use HasFactory;

    protected $table = 'roles_sub_menu_control';

    protected $fillable = ['roles_sub_menu_id', 'name', 'active', 'added_by', 'updated_by', 'com_code'];
}
