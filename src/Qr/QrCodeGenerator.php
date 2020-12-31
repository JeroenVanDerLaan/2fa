<?php declare(strict_types = 1);

namespace App\Qr;

interface QrCodeGenerator
{
    public function generate(string $secret): string;
}