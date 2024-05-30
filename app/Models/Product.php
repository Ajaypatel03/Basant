<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['product_name','image','category_id','total_unit','sold_unit','available_unit','price'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}