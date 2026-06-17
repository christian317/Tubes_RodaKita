<?php

namespace App\Services;

class VerifikasiIdentitasService
{
    public function matchFace(string $fotoKtpPath, string $fotoSelfiePath): float
    {
        if (app()->environment('local')) {
            return rand(75, 99) / 100;
        }

        return 0.0;
    }

    public function extractKtpData(string $fotoKtpPath): ?array
    {
        if (app()->environment('local')) {
            return [
                'nik' => str_repeat('x', 16),
                'nama' => 'Simulasi OCR',
            ];
        }

        return null;
    }
}
