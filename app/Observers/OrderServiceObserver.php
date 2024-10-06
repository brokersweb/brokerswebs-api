<?php

namespace App\Observers;

use App\Models\Inventory\ServiceOrder;
use Carbon\Carbon;

class OrderServiceObserver
{

    public function creating(ServiceOrder $order)
    {
        $this->setCode($order);
    }

    protected function setCode(ServiceOrder $order)
    {
        $now = Carbon::now();
        $month = $now->format('m');
        $year = $now->format('y');

        $latestSerial = ServiceOrder::whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->latest('id')
            ->value('code');

        if ($latestSerial) {
            $serialNumber = (int) substr($latestSerial, -4);
            $serialNumber++;
        } else {
            $serialNumber = 1;
        }

        $order->code = sprintf("ORS-%s-%s-%04d", $month, $year, $serialNumber);
    }
}
