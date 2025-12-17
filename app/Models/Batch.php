<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class Batch extends Model
{
    use HasFactory;

    protected $fillable = [
        'medicine_id',
        'supplier_id',
        'batch_no',
        'expiry_date',
        'quantity',
        'cost_price',
        'selling_price',
    ];

    protected $casts = [
        'expiry_date' => 'date',
    ];

    public function medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function scopeExpiringSoon(Builder $query, int $days = 30): Builder
    {
        $date = Carbon::now()->addDays($days)->toDateString();
        return $query->whereNotNull('expiry_date')->whereDate('expiry_date', '<=', $date);
    }

    public function scopeExpired(Builder $query): Builder
    {
        $today = Carbon::now()->toDateString();
        return $query->whereNotNull('expiry_date')->whereDate('expiry_date', '<', $today);
    }
}

