<?php

namespace App\Observers;

use App\Models\Inventory\ToolLoan;
use Carbon\Carbon;

class ToolLoanObserver
{

    public function creating(ToolLoan $toolLoan)
    {
        $this->setSerial($toolLoan);
    }

    protected function setSerial(ToolLoan $toolLoan)
    {
        $now = Carbon::now();
        $month = $now->format('m');
        $year = $now->format('y');

        $latestSerial = ToolLoan::whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->latest('id')
            ->value('code');

        if ($latestSerial) {
            $serialNumber = (int) substr($latestSerial, -4);
            $serialNumber++;
        } else {
            $serialNumber = 1;
        }

        $toolLoan->code = sprintf("TL-%s-%s-%04d", $month, $year, $serialNumber);
    }
}
