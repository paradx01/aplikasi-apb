<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecommendationHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_transaction_id',
        'disease_id',
        'disease_name',
        'confidence',
        'selected_symptoms',
        'recommended_products',
        'purchased_product_ids',
        'is_confirmed',
    ];

    protected $casts = [
        'confidence' => 'float',
        'selected_symptoms' => 'array',
        'recommended_products' => 'array',
        'purchased_product_ids' => 'array',
        'is_confirmed' => 'boolean',
        'created_at' => 'datetime',
    ];

    // Relasi
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transaction()
    {
        return $this->belongsTo(ProductTransaction::class, 'product_transactions_id');
    }

    public function disease()
    {
        return $this->belongsTo(Disease::class);
    }
}
