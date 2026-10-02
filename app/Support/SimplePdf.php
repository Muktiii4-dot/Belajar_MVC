<?php

namespace App\Support;

use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class SimplePdf
{
    public static function download(Collection $rows, string $filename): Response
    {
        $lines = [
            'LAPORAN DATA INDUK SISWA',
            'Sistem Informasi Administrasi Akademik',
            '',
            'No | NISN | Nama | Lahir | JK | Jurusan | No HP',
            str_repeat('-', 105),
        ];

        foreach ($rows as $i => $siswa) {
            $lines[] = sprintf(
                '%d | %s | %s | %s, %s | %s | %s | %s',
                $i + 1,
                $siswa->nisn,
                self::short($siswa->nama, 22),
                self::short($siswa->tempat_lahir, 14),
                optional($siswa->tanggal_lahir)->format('d-m-Y'),
                $siswa->jenis_kelamin,
                self::short($siswa->jurusan, 8),
                self::short($siswa->no_hp, 15)
            );
        }

        $pdf = self::buildPdf($lines);

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Content-Length' => (string) strlen($pdf),
        ]);
    }

    private static function short($value, int $max): string
    {
        $value = preg_replace('/[\r\n]+/', ' ', (string) $value);
        return function_exists('mb_strimwidth')
            ? mb_strimwidth($value, 0, $max, '...', 'UTF-8')
            : substr($value, 0, $max);
    }

    private static function buildPdf(array $lines): string
    {
        $content = "BT\n/F1 9 Tf\n40 800 Td\n";
        foreach ($lines as $index => $line) {
            if ($index > 0) {
                $content .= "0 -14 Td\n";
            }
            $safe = str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $line);
            $content .= '(' . $safe . ") Tj\n";
        }
        $content .= "ET";

        $objects = [];
        $objects[] = '<< /Type /Catalog /Pages 2 0 R >>';
        $objects[] = '<< /Type /Pages /Kids [3 0 R] /Count 1 >>';
        $objects[] = '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 5 0 R >> >> /Contents 4 0 R >>';
        $objects[] = '<< /Length ' . strlen($content) . " >>\nstream\n" . $content . "\nendstream";
        $objects[] = '<< /Type /Font /Subtype /Type1 /BaseFont /Courier >>';

        $pdf = "%PDF-1.4\n%\xE2\xE3\xCF\xD3\n";
        $offsets = [0];
        foreach ($objects as $i => $object) {
            $offsets[] = strlen($pdf);
            $pdf .= ($i + 1) . " 0 obj\n" . $object . "\nendobj\n";
        }
        $xref = strlen($pdf);
        $pdf .= "xref\n0 " . (count($objects) + 1) . "\n";
        $pdf .= "0000000000 65535 f \n";
        for ($i = 1; $i <= count($objects); $i++) {
            $pdf .= sprintf('%010d 00000 n \n', $offsets[$i]);
        }
        $pdf .= "trailer\n<< /Size " . (count($objects) + 1) . " /Root 1 0 R >>\nstartxref\n" . $xref . "\n%%EOF";
        return $pdf;
    }
}
