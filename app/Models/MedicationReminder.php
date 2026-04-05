<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicationReminder extends Model
{
    //
    use HasFactory;
    
    protected $fillable = [
        'user_id', 'product_id', 'schedule_time', 'frequency', 'start_date',
        'end_date', 'dosage', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
