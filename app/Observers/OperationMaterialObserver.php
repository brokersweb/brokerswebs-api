<?php

namespace App\Observers;

use App\Models\Inventory\OperationMaterial;
use Carbon\Carbon;

class OperationMaterialObserver
{

    public function creating(OperationMaterial $opeMaterial)
    {
        $this->setSerial($opeMaterial);
    }

    protected function setSerial(OperationMaterial $opeMaterial)
    {
        $now = Carbon::now();
        $month = $now->format('m');
        $year = $now->format('y');

        $latestSerial = OperationMaterial::whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->latest('id')
            ->value('code');

        if ($latestSerial) {
            $serialNumber = (int) substr($latestSerial, -4);
            $serialNumber++;
        } else {
            $serialNumber = 1;
        }

        $opeMaterial->code = sprintf("MO-%s-%s-%04d", $month, $year, $serialNumber);
    }
}
