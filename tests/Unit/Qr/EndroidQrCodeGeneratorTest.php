<?php declare(strict_types = 1);

namespace App\Tests\Unit\Qr;

use App\Qr\EndroidQrCodeGenerator;
use Exception;
use PHPUnit\Framework\TestCase;
use PragmaRX\Google2FA\Google2FA;

class EndroidQrCodeGeneratorTest extends TestCase
{
    public function testInvalidFormat(): void
    {
        $this->expectException(Exception::class);

        $google2fa = $this->createMock(Google2FA::class);
        $companyName = 'foo';
        $companyEmail = 'foo@bar.com';
        $format = 'qux';

        new EndroidQrCodeGenerator(
            $google2fa,
            $companyName,
            $companyEmail,
            $format
        );
    }
}