<?php

namespace App\Helpers;

use App\Models\Immovable;

class ImmovableCodeGenerator
{
    public static function generateCode($city, $department)
    {
        $city = substr($city, 0, 2);
        $department = substr($department, 0, 2);
        $city = strtoupper($city);
        $department = strtoupper($department);
        $code = $city . '-' . $department . '-';

        $lastImmovable = Immovable::where('code', 'like', '%' . $code . '%')->latest()->first();

        if (!$lastImmovable) {
            $newCode = $code . '0001';
        } else {

            $newCode = '';
            $counter = 1;

            do {
                $lastImmovableCode = $lastImmovable->code;
                $lastImmovableCode = substr($lastImmovableCode, 6, 4);
                $newCode = $code . str_pad(intval($lastImmovableCode) + $counter, 4, '0', STR_PAD_LEFT);
                $counter++;
            } while (Immovable::where('code', $newCode)->exists());
        }

        return $newCode;
    }
}
