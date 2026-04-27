<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'delivery_method',
        'country',
        'city',
        'postal_code',
        'street_name',
        'house_number',
        'billing_country',
        'billing_city',
        'billing_postal_code',
        'billing_street_name',
        'billing_house_number',
        'note',
        'payment_method',
        'payment_fee',
        'products_total',
        'total_price',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
