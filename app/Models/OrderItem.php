<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'order_id',
    'tshirt_image_id',
    'color_code',
    'size',
    'qty',
    'unit_price',
    'sub_total',
    'custom',
])]

#[Table('order_items')]

class OrderItem extends Model
{
    protected $table = 'order_items'; // Proteção para a tabela pivot [cite: 262]

    protected $casts = [
        'unit_price' => 'decimal:2',
        'sub_total' => 'decimal:2',
        'custom' => 'array',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function tshirtImage(): BelongsTo
    {
        return $this->belongsTo(TshirtImage::class, 'tshirt_image_id', 'id');
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class, 'color_code', 'code');
    }
}
