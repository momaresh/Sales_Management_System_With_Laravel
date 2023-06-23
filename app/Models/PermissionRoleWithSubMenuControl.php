<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionRoleWithSubMenuControl extends Model
{
    use HasFactory;

    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    protected $table = 'permission_roles_with_sub_menu_controls';

    protected $fillable = ['roles_id', 'roles_main_menu_id', 'roles_sub_menu_id', 'roles_sub_menu_control_id', 'added_by', 'created_at', 'com_code'];
}
