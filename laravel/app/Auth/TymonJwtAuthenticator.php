<?php

namespace App\Auth;

use App\Interfaces\AuthenticatorInterface;
use Illuminate\Support\Facades\Auth;

class TymonJwtAuthenticator implements AuthenticatorInterface {
    public function attemptLogin(array $credentials): ?string
    {
        return Auth::attempt($credentials) ?: null;
    }
}
