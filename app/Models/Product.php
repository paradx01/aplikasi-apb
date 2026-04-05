<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    use HasFactory;
    
    protected $guarded = [
        'id',
    ];
    
    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'price',
        'stock', // Perhatikan: 'stok' (disesuaikan dari 'stock' di DB/Migration)
        'photo',
        'active_ingredients',
        'composition',
        'contraindications',
        'indications',
        'side_effects',
        'dosage_form',
        'unit',
        'pregnancy_category',
        'is_active'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function medicationRules() {
        return $this->hasMany(MedicationRule::class);
    }

    public function recommendationRules() {
        return $this->hasMany(RecommendationRule::class);
    }

}
