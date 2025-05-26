<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['user_id', 'cart_id', 'status', 'total_amount', 'address', 'phone', 'payment_method'];

    public function user() {
    
        return $this->belongsTo(User::class);
    }
   
    public function products() {
             return $this->belongsToMany(Product::class, 'order_product')
                ->withPivot('quantity', 'price', 'total')
                ->withTimestamps();
    }
    
}
