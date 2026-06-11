<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Relations\HasMany;


#[Table('categories')]
#[Fillable(['name', 'image_url', 'custom'])]
class Category extends Model
{
    use SoftDeletes;

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'custom' => 'array',
        ];
    }

    public function tshirtImages(): HasMany
    {
        return $this->hasMany(TshirtImage::class, 'category_id', 'id');
    }
}
