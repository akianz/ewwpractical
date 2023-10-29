<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;


class Cart extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'user_id',
        'product_id',
        'price',
    ];

    public function user_details(){
        return $this->hasOne(User::class,'id','user_id');
    }
    public function product_details(){
        return $this->hasOne(Product::class,'id','product_id');
    }
}
