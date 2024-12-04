<?php

namespace App\Observers;

use App\Models\Address;
use App\Models\Contracts\RentalContract;
use App\Models\Immovable;
use Illuminate\Support\Str;

class RentalContractObserver
{

    public function creating(RentalContract $rental)
    {
        $this->setSerial($rental);
    }

    protected function setSerial(RentalContract $rental)
    {
        $address = Address::where('addressable_id', $rental->immovable_id)
            ->where('addressable_type', Immovable::class)
            ->first();

        if (!$address) {
            throw new \Exception('No se encontró una dirección para este inmueble');
        }

        $cityPrefix = strtoupper(substr($address->city, 0, 3));

        $contractPrefix = "ARR-{$cityPrefix}";

        $lastContract = RentalContract::where('rentalnum', 'like', "{$contractPrefix}-%")
            ->count();

        $newNumber = str_pad($lastContract + 1, 3, '0', STR_PAD_LEFT);

        $rental->rentalnum = "{$contractPrefix}-{$newNumber}";
    }
}
