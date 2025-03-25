<?php

namespace App\Interfaces;

interface AuthenticatorInterface
{
    public function attemptLogin(array $credentials): ?string;
}
