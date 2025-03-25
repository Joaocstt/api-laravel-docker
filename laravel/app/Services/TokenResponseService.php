<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;

class TokenResponseService
{
    public function createTokenResponse(string $token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function unauthorizedResponse(): JsonResponse
    {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
