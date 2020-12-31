<?php declare(strict_types = 1);

namespace App\Tests\Functional\Qr;

use App\Qr\EndroidQrCodeGenerator;
use Endroid\QrCode\QrCode;
use PHPUnit\Framework\TestCase;
use PragmaRX\Google2FA\Google2FA;

class EndroidQrCodeGeneratorTest extends TestCase
{
    private Google2FA $google2fa;

    private string $companyName;

    private string $companyEmail;

    private string $format;

    private EndroidQrCodeGenerator $qrCodeGenerator;

    protected function setUp(): void
    {
        $this->google2fa = new Google2FA();
        $this->companyName = 'foo';
        $this->companyEmail = 'foo@bar.com';
        $this->format = 'png';
        $this->qrCodeGenerator =  new EndroidQrCodeGenerator(
          $this->google2fa,
          $this->companyName,
          $this->companyEmail,
          $this->format
        );
    }

    public function testGenerate(): void
    {
        $secret = 'secret';

        $url = $this->google2fa->getQRCodeUrl(
            $this->companyName,
            $this->companyEmail,
            $secret
        );
        $code = new QrCode($url);
        $writer = $code->getWriter($this->format);

        $expected = $writer->writeString($code);
        $actual = $this->qrCodeGenerator->generate($secret);

        $this->assertEquals($expected, $actual);
    }
}