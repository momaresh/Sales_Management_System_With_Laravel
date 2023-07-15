<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OriginalReturnDetails extends Model
{
    use HasFactory;
    protected $table = 'original_return_details';

    protected $fillable = [
        'invoice_order_id', 'pill_code', 'invoice_order_details_id', 'quantity',
        'total_price'
    ];

    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;
}
