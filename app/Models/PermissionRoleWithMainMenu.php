<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionRoleWithMainMenu extends Model
{
    use HasFactory;

    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    protected $table = 'permission_roles_with_main_menu';

    protected $fillable = ['roles_id', 'roles_main_menu_id', 'added_by', 'created_at', 'com_code'];
}
