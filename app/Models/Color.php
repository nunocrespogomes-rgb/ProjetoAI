<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;

#[Table('colors')]
#[Fillable(['code', 'name', 'custom'])]
class Color extends Model
{
    use SoftDeletes;
    public $timestamps = false;

    protected $primaryKey = 'code';
    public $incrementing = false;
    protected $keyType = 'string';

    // app/Models/Color.php
    public function orderItems(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OrderItem::class, 'color_code', 'code');
    }

    protected function casts(): array
    {
        return [
            'custom' => 'array',
        ];
    }
}
