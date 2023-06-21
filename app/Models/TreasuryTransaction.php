<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreasuryTransaction extends Model
{
    use HasFactory;

    protected $table = 'treasuries_transactions';

    protected $fillable =
    [
        'transaction_code', 'shift_code', 'move_type', 'account_number', 'last_arrive', 'transaction_type',
        'treasuries_id', 'invoice_id',
        'is_account', 'is_approved', 'money', 'money_for_account',
        'byan', 'move_date', 'added_by', 'updated_by', 'date', 'com_code'
    ];
}
