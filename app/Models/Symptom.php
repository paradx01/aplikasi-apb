<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Symptom extends Model
{
    //
    use HasFactory;
    
    protected $guarded = [
        'id',
        'symptom_name', 
        'description',
    ];
    
    protected $fillable = ['symptom_name', 'type', 'description'];

    // Relasi ke Disease
    public function diseases()
    {
        return $this->belongsToMany(Disease::class, 'symptom_diseases')
            ->withPivot('id', 'is_critical')
            ->withTimestamps();
    }

    // Scope filters
    public function scopeUmum($query)
    {
        return $query->where('type', 'umum');
    }

    public function scopeKritis($query)
    {
        return $query->where('type', 'kritis');
    }

    public function scopeSearch($query, $keyword)
    {
        return $query->where('symptom_name', 'LIKE', "%{$keyword}%");
    }

    public function symptomdisease()
    {
        return $this->belongsTo(SymptomDisease::class);
    }
}
