<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductImages;

class Products extends Model
{
    use HasFactory;

    protected $table = "products";

    protected $fillable = [
        'title',
        'description',
        'category_id',
        'rating',
        'price'
    ];

    public function productImages()
    {
        return $this->hasMany(ProductImages::class,'product_id', 'id');
    }
}
