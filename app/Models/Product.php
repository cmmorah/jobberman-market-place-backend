<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $fillable = [
        'category',
        'user_id',
        'location',
        'pictures',
        'youtube_link',
        'title',
        'brand',
        'condition',
        'description',
        'price',
        'seller_phone',
        'seller_name'
    ];
}
