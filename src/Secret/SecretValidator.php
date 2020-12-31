<?php declare(strict_types = 1);

namespace App\Secret;

interface SecretValidator
{
    public function isValid(string $secret, string $key): bool;
}