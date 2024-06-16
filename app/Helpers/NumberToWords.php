<?php

namespace App\Helpers;

class NumberToWords
{
    private static $units = [
        '',
        'un',
        'dos',
        'tres',
        'cuatro',
        'cinco',
        'seis',
        'siete',
        'ocho',
        'nueve',
        'diez',
        'once',
        'doce',
        'trece',
        'catorce',
        'quince',
        'dieciséis',
        'diecisiete',
        'dieciocho',
        'diecinueve',
        'veinte',
        'veintiuno',
        'veintidós',
        'veintitrés',
        'veinticuatro',
        'veinticinco',
        'veintiséis',
        'veintisiete',
        'veintiocho',
        'veintinueve'
    ];

    private static $tens = [
        '',
        'diez',
        'veinte',
        'treinta',
        'cuarenta',
        'cincuenta',
        'sesenta',
        'setenta',
        'ochenta',
        'noventa'
    ];

    private static $scales = [
        '',
        'mil',
        'millón',
        'mil millones',
        'billón'
    ];

    public static function toWords($number)
    {
        if ($number < 0) {
            return 'menos ' . self::toWords(abs($number));
        }

        if ($number < 30) {
            return self::$units[$number];
        }

        if ($number < 100) {
            $units = $number % 10;
            $tens = floor($number / 10);
            return $units ? self::$tens[$tens] . ' y ' . self::$units[$units] : self::$tens[$tens];
        }

        if ($number < 1000) {
            $hundreds = floor($number / 100);
            $remainder = $number % 100;
            $string = $hundreds == 1 ? 'cien' : self::$units[$hundreds] . 'cientos';
            $string .= $remainder ? ' ' . self::toWords($remainder) : '';
            return $string;
        }

        $baseUnit = pow(1000, floor(log($number, 1000)));
        $numBaseUnits = (int) ($number / $baseUnit);
        $remainder = $number % $baseUnit;

        if ($baseUnit == 1000 && $numBaseUnits == 1) {
            $string = self::$scales[log($baseUnit, 1000)];
        } else {
            $string = self::toWords($numBaseUnits) . ' ' . self::$scales[log($baseUnit, 1000)];
        }

        if ($remainder) {
            $string .= $remainder < 100 ? ' ' : ' ';
            $string .= self::toWords($remainder);
        }

        return $string;
    }

    public static function toPesos($number)
    {
        $decimals = round(($number - floor($number)) * 100);
        $words = self::toWords(floor($number));

        $result = $words . ' pesos';
        if ($decimals > 0) {
            $result .= ' con ' . self::toWords($decimals) . ' centavos';
        }

        return ucfirst($result);
    }
}
