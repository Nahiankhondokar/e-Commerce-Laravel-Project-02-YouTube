<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    // order product table details 
    public function order_product(){
        return $this -> hasMany(OrderProduct::class, 'order_id');
    }
}