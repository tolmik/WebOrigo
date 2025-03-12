<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class LeasingPeriod extends Model
{
    use HasFactory;

    protected $fillable = [
        'deviceId',
        'ownerId',
        'leasingConstructionId',
        'leasingConstructionMaximumTraining',
        'leasingConstructionMaximumDate',
        'leasingActualPeriodStartDate',
        'leasingNextCheck',
        'isLeasingActive',
    ];

    public function device(): HasOne
    {
        return $this->hasOne(Device::class);
    }
}
