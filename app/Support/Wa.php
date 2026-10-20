<?php

namespace App\Support;

class Wa
{
    // Ubah 0812-xxx / +62 812 xxx / 812xxx menjadi 62812xxx. Null jika tidak valid.
    public static function normalisasi(?string $hp): ?string
    {
        if (! $hp) {
            return null;
        }

        $angka = preg_replace('/\D+/', '', $hp); // buang spasi, +, -, dll

        if ($angka === '') {
            return null;
        }

        if (str_starts_with($angka, '0')) {
            $angka = '62' . substr($angka, 1);
        } elseif (str_starts_with($angka, '8')) {
            $angka = '62' . $angka;
        }

        $valid = str_starts_with($angka, '62') && strlen($angka) >= 10 && strlen($angka) <= 15;

        return $valid ? $angka : null;
    }

    public static function link(?string $hp, string $pesan): ?string
    {
        $nomor = self::normalisasi($hp);

        return $nomor ? 'https://wa.me/' . $nomor . '?text=' . rawurlencode($pesan) : null;
    }
}