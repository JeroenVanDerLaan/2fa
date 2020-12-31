<?php declare(strict_types = 1);

namespace App\Qr;

use Endroid\QrCode\QrCode;
use InvalidArgumentException;
use PragmaRX\Google2FA\Google2FA;
use function in_array;

class EndroidQrCodeGenerator implements QrCodeGenerator
{
    public const FORMAT_BINARY = 'binary';
    public const FORMAT_PNG = 'png';
    public const FORMAT_SVG = 'svg';

    public const FORMATS = [
        self::FORMAT_BINARY,
        self::FORMAT_PNG,
        self::FORMAT_SVG,
    ];

    private Google2FA $google2fa;

    private string $companyName;

    private string $companyEmail;

    private string $format;

    public function __construct(
        Google2FA $google2fa,
        string $companyName,
        string $companyEmail,
        string $format = self::FORMAT_PNG
    ) {
        if (false === in_array($format, self::FORMATS, true)) {
            throw new InvalidArgumentException('Invalid QR code format');
        }
        $this->google2fa = $google2fa;
        $this->companyName = $companyName;
        $this->companyEmail = $companyEmail;
        $this->format = $format;
    }

    public function generate(string $secret): string
    {
        $url = $this->google2fa->getQRCodeUrl(
            $this->companyName,
            $this->companyEmail,
            $secret
        );
        $code = new QrCode($url);
        $writer = $code->getWriter($this->format);
        return $writer->writeString($code);
    }
}