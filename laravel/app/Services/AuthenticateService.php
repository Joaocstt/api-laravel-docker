<?php
namespace App\Services;
use App\Interfaces\AuthenticatorInterface;
use Illuminate\Http\JsonResponse;

class AuthenticateService
{
    public function __construct(
        protected AuthenticatorInterface $authenticator,
        protected TokenResponseService $tokenResponseService
    ) {}
    public function attemptAuthenticate(array $data): JsonResponse
    {
        $token = $this->authenticator->attemptLogin($data);

        if(!$token) {
            return $this->tokenResponseService->unauthorizedResponse();
        }

        return $this->tokenResponseService->createTokenResponse($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */

    public function me(): JsonResponse
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
