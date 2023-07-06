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
        'start_date', 'end_date', 'is_finished', 'delivered_to_shift_id',
        'money_should_delivered', 'what_really_delivered', 'money_state', 'money_state_value',
        'review_receive_date', 'notes', 'added_by',
        'updated_by', 'finished_by', 'com_code', 'date'
    ];


}
