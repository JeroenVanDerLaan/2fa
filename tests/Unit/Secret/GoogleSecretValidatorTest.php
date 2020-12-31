<?php declare(strict_types = 1);

namespace App\Tests\Unit\Secret;

use App\Secret\GoogleSecretValidator;
use PHPUnit\Framework\TestCase;
use PragmaRX\Google2FA\Exceptions\Google2FAException;
use PragmaRX\Google2FA\Google2FA;
use RuntimeException;

class GoogleSecretValidatorTest extends TestCase
{
    public function testIsValid(): void
    {
        $key = 'key';
        $secret = 'secret';
        $window = 1;

        $google2fa = $this->createMock(Google2FA::class);
        $google2fa
            ->method('verify')
            ->with(
                $key,
                $secret,
                $window
            )
            ->willReturn(true);

        $secretValidator = new GoogleSecretValidator(
            $google2fa,
            $window
        );

        $expected = true;
        $actual = $secretValidator->isValid($secret, $key);

        $this->assertEquals($expected, $actual);
    }

    public function testValidationFailure(): void
    {
        $key = 'key';
        $secret = 'secret';
        $window = 1;

        $google2fa = $this->createMock(Google2FA::class);
        $google2fa
            ->method('verify')
            ->with(
                $key,
                $secret,
                $window
            )
            ->willReturn(false);

        $secretValidator = new GoogleSecretValidator(
            $google2fa,
            $window
        );

        $this->expectException(RuntimeException::class);
        $secretValidator->isValid($secret, $key);
    }

    public function testValidationError(): void
    {
        $key = 'key';
        $secret = 'secret';
        $window = 1;

        $google2fa = $this->createMock(Google2FA::class);
        $google2fa
            ->method('verify')
            ->willThrowException(new Google2FAException());

        $secretValidator = new GoogleSecretValidator(
            $google2fa,
            $window
        );

        $this->expectException(RuntimeException::class);
        $secretValidator->isValid($secret, $key);
    }
}