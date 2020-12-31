<?php declare(strict_types = 1);

namespace App\Tests\Unit\Secret;

use App\Secret\GoogleSecretGenerator;
use PHPUnit\Framework\TestCase;
use PragmaRX\Google2FA\Exceptions\Google2FAException;
use PragmaRX\Google2FA\Google2FA;
use RuntimeException;

class GoogleSecretGeneratorTest extends TestCase
{
    public function testGenerate(): void
    {
        $expected = 'secret';

        $google2fa = $this->createMock(Google2FA::class);
        $google2fa
            ->method('generateSecretKey')
            ->willReturn($expected);

        $length = 16;
        $prefix = '';

        $secretGenerator = new GoogleSecretGenerator(
            $google2fa,
            $length,
            $prefix
        );

        $actual = $secretGenerator->generate();

        $this->assertEquals($expected, $actual);
    }

    public function testGenerateError(): void
    {
        $google2fa = $this->createMock(Google2FA::class);
        $google2fa
            ->method('generateSecretKey')
            ->willThrowException(new Google2FAException());

        $length = 16;
        $prefix = '';

        $secretGenerator = new GoogleSecretGenerator(
            $google2fa,
            $length,
            $prefix
        );

        $this->expectException(RuntimeException::class);
        $secretGenerator->generate();
    }
}