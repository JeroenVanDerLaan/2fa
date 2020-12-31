<?php declare(strict_types = 1);

namespace App\Secret;

use PragmaRX\Google2FA\Exceptions\Google2FAException;
use PragmaRX\Google2FA\Google2FA;
use RuntimeException;

class GoogleSecretGenerator implements SecretGenerator
{
    private Google2FA $google2fa;

    private int $length;

    private string $prefix;

    public function __construct(
        Google2FA $google2fa,
        int $length = 16,
        string $prefix = ''
    ) {
        $this->google2fa = $google2fa;
        $this->length = $length;
        $this->prefix = $prefix;
    }

    public function generate(): string
    {
        try {
            return $this->google2fa->generateSecretKey(
                $this->length,
                $this->prefix
            );
        } catch (Google2FAException $exception) {
            $message = 'Error trying to generate google secret key';
            throw new RuntimeException($message, 0, $exception);
        }
    }

}