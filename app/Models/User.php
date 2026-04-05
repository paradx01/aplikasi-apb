<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use NotificationChannels\WebPush\HasPushSubscriptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, HasPushSubscriptions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 
        'email',
        'password',
        'age',
        'gender',
        // Kontraindikasi - 11 fields
        'has_hypertension',
        'has_heart_disorder',
        'has_diabetes',
        'has_stomach_ulcer',
        'has_kidney_disorder',
        'has_liver_disorder',
        'has_asthma',
        'has_glaucoma',
        'is_pregnant',
        'has_prostate_disorder',
        'has_hyperthyroidism',
        'has_g6pd_deficiency',

        'has_allergy_paracetamol',
        'has_allergy_nsaid',
        'has_allergy_aspirin',
        'has_allergy_antihistamine',
        'has_allergy_decongestant',
        'has_allergy_bromhexine',
        'has_allergy_guaifenesin',
        'has_allergy_antacid',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    public function carts(){
        return $this->hasMany(Cart::class);
    }
    
    public function product_transactions(){
        return $this->hasMany(ProductTransaction::class);
    }
    
    public function addresses()
    {
        return $this->hasMany(UserAddress::class);
    }

    public function primaryAddress()
    {
        return $this->hasOne(UserAddress::class)->where('is_primary', true);
    }
}
