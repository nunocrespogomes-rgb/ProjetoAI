<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;

#[Fillable(['id', 'nif', 'address', 'default_payment_type', 'default_payment_ref', 'custom'])]
#[Table('customers')]

class Customer extends Model
{

    public $incrementing = false; 

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'custom' => 'array',
        ];
    }

  
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id', 'id');
    }

    
    public function tshirtImages(): HasMany
    {
        return $this->hasMany(TshirtImage::class, 'customer_id', 'id');
    }

    /**
     * Um cliente pode fazer várias encomendas (G4).
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'customer_id', 'id');
    }
}