<?php

namespace App\Services;

class RegresiLinearService
{
    public static function hitungRegresi(array $x, array $y): ?array
    {
        $n = count($x);
        if ($n !== count($y) || $n < 2) return null;

        $sumX = array_sum($x);
        $sumY = array_sum($y);
        $sumXY = array_sum(array_map(fn($xi, $yi) => $xi * $yi, $x, $y));
        $sumX2 = array_sum(array_map(fn($xi) => $xi * $xi, $x));

        $denominator = $n * $sumX2 - $sumX * $sumX;
        if ($denominator == 0) return null;

        $a = ($n * $sumXY - $sumX * $sumY) / $denominator;
        $b = ($sumY - $a * $sumX) / $n;

        return compact('a', 'b');
    }

    public static function prediksiJam($a, $b, $lux_threshold): ?float
    {
        return ($a != 0) ? ($lux_threshold - $b) / $a : null;
    }

    public static function prediksiMinggu(float $jam): int
    {
        return ceil($jam / 12 / 7); // 12 jam per hari, 7 hari per minggu
    }
}
