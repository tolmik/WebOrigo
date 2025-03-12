<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Device extends Model
{
    use HasFactory;

    protected $fillable  = [
        'deviceId',
        "deviceType",
        "dateofRegistration",
        "deviceOwner",
        "deviceOwnerId",
        "activationCode"
    ];

    protected $hidden = ['deviceOwnerId','createdAt, modifiedAt', 'deletedAt', 'activationCode'];

    public function leasingPeriods(): HasMany {
        return $this->hasMany(LeasingPeriod::class);
    }

    public function deviceOwnerDetails()
    {
        if(!is_null($this->device0wner)) {
            return DeviceOwner::find($this->deviceOwner);
        }
        return null;
    }

    public function registrationRequests(): HasMany
    {
        return $this->hasMany(RegistrationRequest::class);
    }
}
