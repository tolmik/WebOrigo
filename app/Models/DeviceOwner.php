<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeviceOwner extends Model
{
    use HasFactory;

    protected $fillable = [
        'billingName', 'addressCountry', 'addressZip', 'addressCity', 'addressStreet', 'vatNumber'
    ];

    public function devices(): HasMany {
        return $this->hasMany(Device::class);
    }
}
