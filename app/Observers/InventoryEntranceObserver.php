<?php

namespace App\Observers;

use App\Models\Inventory\InventoryEntrance;
use Carbon\Carbon;

class InventoryEntranceObserver
{

    public function creating(InventoryEntrance $order)
    {
        $this->setCode($order);
    }

    protected function setCode(InventoryEntrance $order)
    {
        $now = Carbon::now();
        $month = $now->format('m');
        $year = $now->format('y');

        $latestSerial = InventoryEntrance::whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->latest('id')
            ->value('code');

        if ($latestSerial) {
            $serialNumber = (int) substr($latestSerial, -4);
            $serialNumber++;
        } else {
            $serialNumber = 1;
        }

        $order->code = sprintf("ENT-%s-%s-%04d", $month, $year, $serialNumber);
    }
}
