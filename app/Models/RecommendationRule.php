<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecommendationRule extends Model
{
    //
    use HasFactory;
    
    protected $guarded = [
        'id',
        'disease_id', 
        'product_id', 
        'min_age',
        'max_age',
        'priority',
        'notes'
    ];
    
    protected $fillable = [
        'disease_id', 
        'product_id', 
        'min_age',
        'max_age',
        'priority',
        'notes'
    ];

    protected $casts = [
        'min_age' => 'integer',
        'max_age' => 'integer',
        'priority' => 'integer',
    ];

    /**
     * Relasi ke Disease
     */
    public function disease()
    {
        return $this->belongsTo(Disease::class);
    }

    /**
     * Relasi ke Product (obat)
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
