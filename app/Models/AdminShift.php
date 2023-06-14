<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminShift extends Model
{
    use HasFactory;

    protected $table = 'admin_shifts';
    protected $fillable = [
        'id', 'shift_code', 'admin_id', 'treasuries_id', 'treasuries_balance_in_shift_start',
        'start_date', 'end_date', 'is_finished', 'is_delivered_and_review', 'delivered_to_admin_sift_id',
        'money_should_deviled', 'what_really_delivered', 'money_state', 'money_state_value',
        'receive_type', 'review_receive_date', 'treasuries_transactions_id', 'notes', 'added_by',
        'updated_by', 'com_code', 'date'
    ];

    
}
