<?php

namespace App;

use App\Models\LicenseTypes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'name',
        'email',
        'password',
        'first_name',
        'last_name',
        'birth_date',
        'phone',
        'ruc',
        'direction',
        'postal_code',
        'city',
        'license_types_id',
        'photo',
        'image_driver_license',
        'image_permit_circulation',
        'image_certificate_background',
        'phone_verified_at',
        'code_activation',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'type'              => 'integer',
        'birth_date'        => 'date',
    ];

    protected $with = [
        'license',
        'transportationAvailable',
    ];

    /* ========================================================================= *\
     * Relations
    \* ========================================================================= */

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function license(): BelongsTo
    {
        return $this->belongsTo(LicenseTypes::class, 'license_types_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function transportationAvailable(): HasOne
    {
        return $this->hasOne(TransportationAvailable::class);
    }

    /* ========================================================================= *\
     * Helpers
    \* ========================================================================= */

    /**
     * Get existing or make new access token
     */
    public function makeApiToken()
    {
        return $this->createToken('API')->accessToken;
    }

    /* ========================================================================= *\
     * Scopes
    \* ========================================================================= */

    /**
     * @param Builder $query
     * @return Builder|\Illuminate\Database\Query\Builder
     */
    public function scopeEmailVerified(Builder $query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    /**
     * @param Builder $query
     * @return Builder|\Illuminate\Database\Query\Builder
     */
    public function scopePhoneVerified(Builder $query)
    {
        return $query->whereNotNull('phone_verified_at');
    }

    /**
     * @return bool
     */
    public function setVerifiedAt()
    {
        return $this->update([
            'phone_verified_at' => today(),
            'code_activation'   => null,
        ]);
    }
}
