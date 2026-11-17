<?php

namespace App\Support;

class Kode
{
    /** Rapikan nama: huruf besar, hanya huruf/angka, spasi ganda jadi satu. "lemari  1" -> "LEMARI 1" */
    public static function rapikan(string $teks): string
    {
        $teks = preg_replace('/[^A-Za-z0-9]+/', ' ', $teks);

        return strtoupper(trim($teks));
    }

    /**
     * Singkatan untuk barcode dari nama lengkap:
     *  - awalan "LAB" dibuang, spasi dihapus
     *  - kata lebih dari 3 huruf: huruf vokal dihilangkan kecuali huruf pertama
     * LAB PPLG 1 -> PPLG1 | KELAS ORACLE -> KLSORCL | LEMARI 1 -> LMR1
     */
    public static function singkat(string $teks): string
    {
        $teks = self::rapikan($teks);
        $teks = preg_replace('/^LAB\s+/', '', $teks);

        $hasil = '';
        foreach (preg_split('/\s+/', $teks, -1, PREG_SPLIT_NO_EMPTY) as $kata) {
            if (strlen($kata) > 3) {
                $kata = substr($kata, 0, 1) . preg_replace('/[AIUEO]/', '', substr($kata, 1));
            }
            $hasil .= $kata;
        }

        return $hasil;
    }

    /** Nama lengkap ruang untuk tampilan. Data lama "PPLG1" -> "LAB PPLG 1". */
    public static function labelRuang(string $nilai): string
    {
        $nilai = self::rapikan($nilai);

        if (preg_match('/^PPLG\s*(\d+)$/', $nilai, $m)) {
            return 'LAB PPLG ' . $m[1];
        }

        return self::pisahAngka($nilai);
    }

    /** Nama lengkap tempat untuk tampilan. Data lama "LMR1" -> "LEMARI 1". */
    public static function labelTempat(string $nilai): string
    {
        $nilai = self::rapikan($nilai);

        if (preg_match('/^LMR\s*(\d+)$/', $nilai, $m)) {
            return 'LEMARI ' . $m[1];
        }

        return self::pisahAngka($nilai);
    }

    /** Data lama tanpa spasi ("LEMARI1") diberi spasi sebelum angka. Yang sudah ada spasi dibiarkan. */
    private static function pisahAngka(string $nilai): string
    {
        if (str_contains($nilai, ' ')) {
            return $nilai;
        }

        return preg_replace('/([A-Z])(\d)/', '$1 $2', $nilai);
    }
}