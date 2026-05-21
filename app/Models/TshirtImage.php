<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;



#[Fillable([
    'customer_id',
    'category_id',
    'name',
    'description',
    'image_url',
    'custom',
])]
#[Table('tshirt_images')]

class TshirtImage extends Model
{
    use SoftDeletes;



    protected $casts = [
        'custom' => 'array', // Permite guardar as configurações de Preview em JSON (G7) [cite: 140]
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}
