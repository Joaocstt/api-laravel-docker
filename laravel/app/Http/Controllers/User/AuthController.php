<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\UserAuthenticateRequest;
use App\Services\AuthenticateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

/**
 * @method middleware(string $string, array[] $array)
 */
class AuthController extends Controller
{

    public function __construct(Protected AuthenticateService $authenticateService) {}

    public function login(UserAuthenticateRequest $request): JsonResponse
    {
        return $this->authenticateService->attemptAuthenticate($request->validated());
    }

}
