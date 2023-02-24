<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    public function order()
    {
        //return $this->hasOne('App\Models\Order');
        return $this->belongsTo(Order::class);
    }
    protected $fillable = [
        'order_number',
        'item_number',
        'item_name',
        'price',
        'amount',
        'item_No',
        'count',
        'price'
    ];
}
