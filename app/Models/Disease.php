<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disease extends Model
{
    //
    use HasFactory;
    
    protected $guarded = [
        'id',
        'disease_name', 
        'description',
    ];

    protected $fillable = [
        'disease_name',
        'description',
    ];

    // Relasi dengan symptoms
    public function symptoms()
    {
        return $this->belongsToMany(Symptom::class, 'symptom_diseases')
            ->withPivot('id', 'is_critical')
            ->withTimestamps();
    }

    public function symptomdisease()
    {
        return $this->belongsTo(SymptomDisease::class);
    }

    // Relasi dengan recommendations
    public function recommendationRules()
    {
        return $this->hasMany(RecommendationRule::class);
    }
    
}
