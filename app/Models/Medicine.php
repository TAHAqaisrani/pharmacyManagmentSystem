<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'category',
        'unit',
        'default_price',
        'description',
        'reorder_level',
    ];

    public function batches(): HasMany
    {
        return $this->hasMany(Batch::class);
    }

    public function invoiceItems(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function getStockAttribute(): int
    {
        return (int) $this->batches()->sum('quantity');
    }
}

