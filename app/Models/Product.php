<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $fillable = [
        'name',
        'price',
        'stock',
        'description',
        'image',
        'is_active',
        'category_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
