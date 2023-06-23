<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleMainMenu extends Model
{
    use HasFactory;

    protected $table = 'roles_main_menu';

    protected $fillable = ['name', 'active', 'added_by', 'updated_by', 'com_code'];
}
