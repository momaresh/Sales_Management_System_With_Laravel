<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminPanelSetting extends Model
{
    use HasFactory;

    protected $table = 'admin_panel_settings';
    protected $fillable = ['system_name', 'photo', 'active', 'address', 'phone', 'customer_parent_account', 'supplier_parent_account', 'delegate_parent_account', 'employee_parent_account', 'treasury_parent_account', 'customer_first_code', 'supplier_first_code', 'delegate_first_code', 'employee_first_code', 'commission_for_group_sales', 'commission_for_half_group_sales', 'commission_for_one_sales', 'tax_percent_for_invoice', 'added_by', 'updated_by'];
}
