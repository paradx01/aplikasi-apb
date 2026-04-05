<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SymptomDisease extends Model
{
    //
    use HasFactory;
    
    protected $guarded = [
        'id',
        'symptom_id', 
        'disease_id',
        'is_critical',
        'is_required',
    ];

    protected $table = 'symptom_diseases';
    protected $fillable = ['symptom_id', 'disease_id', 'is_critical'];

    public function symptom()
    {
        return $this->belongsTo(Symptom::class);
    }

    public function disease()
    {
        return $this->belongsTo(Disease::class);
    }
}
