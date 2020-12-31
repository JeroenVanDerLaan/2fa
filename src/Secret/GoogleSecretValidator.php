<?php declare(strict_types = 1);

namespace App\Secret;

use PragmaRX\Google2FA\Exceptions\Google2FAException;
use PragmaRX\Google2FA\Google2FA;
use RuntimeException;

class GoogleSecretValidator implements SecretValidator
{
    private Google2FA $google2fa;

    private int $validationWindow;

    public function __construct(
        Google2FA $google2fa,
        int $validationWindow
    )
    {
        $this->google2fa = $google2fa;
        $this->validationWindow = $validationWindow;
    }

    public function isValid(
        string $secret,
        string $key
    ): bool {
        try {
            $isValid = $this->google2fa->verify(
                $key,
                $secret,
                $this->validationWindow
            );
        } catch (Google2FAException $exception) {
            $message = 'Error trying to verify google key';
            throw new RuntimeException($message, 0, $exception);
        }
        if (false === $isValid) {
            throw new RuntimeException('Failed to verify google key');
        }
        return $isValid;
    }

}