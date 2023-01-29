<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public function order_detail()
    {
        //return $this->hasMany('App\Models\Order_Detail');
        return $this->hasMany(OrderDetail::class,'order_number','order_number');
    }
    protected $fillable = [
        'order_number',
        'name',
        'email',
        'kana',
        'tel',
        'postal',
        'prefecture',
        'city',
        'street',
        'interval',
        'week',
        'youbi',
        'message',
        'subtotal',
        'shipping',
        'tax',
        'total',
    ];
}
