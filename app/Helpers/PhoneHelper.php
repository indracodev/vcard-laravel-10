<?php

if (! function_exists('formatPhone')) {
    function formatPhone($number)
    {
        // 1. Hilangkan semua spasi dulu
        $number = str_replace(' ', '', (string) $number);

        if (! str_starts_with($number, '+62')) {
            return $number;
        }

        // buang +62
        $digits = substr($number, 3);

        // ambil prefix operator (3 digit)
        $prefix = substr($digits, 0, 3);

        // sisa digit setelah prefix
        $rest = substr($digits, 3);
        $restLen = strlen($rest);

        $groups = [$prefix];

        // aturan khusus Indonesia
        if ($restLen === 6) {
            // 3-3
            $groups[] = substr($rest, 0, 3);
            $groups[] = substr($rest, 3, 3);
        } elseif ($restLen === 7) {
            // 3-4
            $groups[] = substr($rest, 0, 3);
            $groups[] = substr($rest, 3, 4);
        } elseif ($restLen === 8) {
            // 4-4 (kasus +6281235733679)
            $groups[] = substr($rest, 0, 4);
            $groups[] = substr($rest, 4, 4);
        } else {
            // fallback aman: pecah 3-3 tanpa 1 digit di akhir
            while (strlen($rest) > 4) {
                $groups[] = substr($rest, 0, 3);
                $rest = substr($rest, 3);
            }
            if ($rest !== '') {
                $groups[] = $rest;
            }
        }

        return '+62 '.implode(' ', $groups);
    }
}
