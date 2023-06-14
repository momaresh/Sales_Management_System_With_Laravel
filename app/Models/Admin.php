<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Admin extends Authenticatable
{
    use HasFactory;

    protected $table = "admin";
    protected $fillable = ['name', 'email', 'password', 'user_name', 'created_at', 'updated_at', 'added_by', 'updated_by', 'com_code'];
}
