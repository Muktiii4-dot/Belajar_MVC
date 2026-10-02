<?php

namespace App\Support;

use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class SimpleXlsx
{
    public static function download(Collection $rows, string $filename): Response
    {
        if (!class_exists('ZipArchive')) {
            return response(self::csv($rows), 200, [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . pathinfo($filename, PATHINFO_FILENAME) . '.csv"',
            ]);
        }

        $tmp = tempnam(sys_get_temp_dir(), 'siswa_xlsx_');
        $zip = new \ZipArchive();
        $zip->open($tmp, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        $zip->addFromString('[Content_Types].xml', self::contentTypes());
        $zip->addFromString('_rels/.rels', self::rels());
        $zip->addFromString('xl/workbook.xml', self::workbook());
        $zip->addFromString('xl/_rels/workbook.xml.rels', self::workbookRels());
        $zip->addFromString('xl/worksheets/sheet1.xml', self::sheet($rows));
        $zip->close();

        $content = file_get_contents($tmp);
        @unlink($tmp);

        return response($content, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Content-Length' => (string) strlen($content),
        ]);
    }

    private static function sheet(Collection $rows): string
    {
        $data = [
            ['No', 'NISN', 'Nama Lengkap', 'Tempat Lahir', 'Tanggal Lahir', 'Jenis Kelamin', 'Jurusan', 'Alamat', 'Nomor HP', 'Email'],
        ];

        foreach ($rows as $i => $siswa) {
            $data[] = [
                $i + 1,
                (string) $siswa->nisn,
                $siswa->nama,
                $siswa->tempat_lahir,
                optional($siswa->tanggal_lahir)->format('Y-m-d'),
                $siswa->jenis_kelamin,
                $siswa->jurusan,
                $siswa->alamat,
                $siswa->no_hp,
                $siswa->email,
            ];
        }

        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><sheetData>';
        foreach ($data as $r => $row) {
            $xml .= '<row r="' . ($r + 1) . '">';
            foreach ($row as $c => $value) {
                $ref = self::column($c + 1) . ($r + 1);
                $xml .= '<c r="' . $ref . '" t="inlineStr"><is><t>' . htmlspecialchars((string) $value, ENT_XML1 | ENT_QUOTES, 'UTF-8') . '</t></is></c>';
            }
            $xml .= '</row>';
        }
        return $xml . '</sheetData></worksheet>';
    }

    private static function column(int $n): string
    {
        $s = '';
        while ($n > 0) {
            $n--;
            $s = chr(65 + ($n % 26)) . $s;
            $n = intdiv($n, 26);
        }
        return $s;
    }

    private static function contentTypes(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/><Default Extension="xml" ContentType="application/xml"/><Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/><Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/></Types>';
    }

    private static function rels(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/></Relationships>';
    }

    private static function workbook(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"><sheets><sheet name="Data Siswa" sheetId="1" r:id="rId1"/></sheets></workbook>';
    }

    private static function workbookRels(): string
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/></Relationships>';
    }

    private static function csv(Collection $rows): string
    {
        $out = "\xEF\xBB\xBF";
        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, ['No', 'NISN', 'Nama Lengkap', 'Tempat Lahir', 'Tanggal Lahir', 'Jenis Kelamin', 'Jurusan', 'Alamat', 'Nomor HP', 'Email']);
        foreach ($rows as $i => $siswa) {
            fputcsv($handle, [$i + 1, $siswa->nisn, $siswa->nama, $siswa->tempat_lahir, optional($siswa->tanggal_lahir)->format('Y-m-d'), $siswa->jenis_kelamin, $siswa->jurusan, $siswa->alamat, $siswa->no_hp, $siswa->email]);
        }
        rewind($handle);
        $out .= stream_get_contents($handle);
        fclose($handle);
        return $out;
    }
}
