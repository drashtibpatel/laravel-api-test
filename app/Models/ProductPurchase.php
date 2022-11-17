<?php

  

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class ProductPurchase extends Model

{
    protected $table = 'product_purchase';
  /**

     * The attributes that are mass assignable.

     *

     * @var array

     */

    protected $fillable = [

        'user_id', 'quantity', 'total_price'

    ];

}