<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'category',
        'stock',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
        ];
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function inStock($quantity = 1)
    {
        return $this->stock >= $quantity;
    }

    public function reduceStock($quantity)
    {
        if ($this->inStock($quantity)) {
            $this->decrement('stock', $quantity);
            return true;
        }
        return false;
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%")
            ->orWhere('description', 'like', "%{$search}%")
            ->orWhere('category', 'like', "%{$search}%");
    }

    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
