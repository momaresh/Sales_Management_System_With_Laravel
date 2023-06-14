<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;
    protected $table = 'accounts';

    protected $fillable = ['account_number', 'account_type', 'is_parent', 'parent_account_number', 'start_balance_status', 'start_balance', 'current_balance', 'active', 'notes',  'active', 'added_by', 'updated_by', 'date', 'com_code'];

}
