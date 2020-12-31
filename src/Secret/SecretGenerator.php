<?php declare(strict_types = 1);

namespace App\Secret;

interface SecretGenerator
{
    public function generate(): string;
}