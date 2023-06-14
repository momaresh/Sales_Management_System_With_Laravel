<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminPanelSetting extends Model
{
    use HasFactory;

    protected $table = 'admin_panel_settings';
    protected $fillable = ['system_name', 'photo', 'active', 'general_alert', 'address', 'phone', 'customer_parent_account', 'supplier_parent_account', 'delegate_parent_account', 'employee_parent_account', 'customer_first_code', 'supplier_first_code', 'delegate_first_code', 'employee_first_code', 'added_by', 'updated_by'];
}
