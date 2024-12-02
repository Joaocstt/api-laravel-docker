<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserAuthController extends Controller
{
    public function __construct(Protected UserService $userService) {}
    public function register(RegisterRequest $request): JsonResponse
    {

        $data = $request->validated();

        return $this->userService->registerUser($data);

    }

    public function activate(string $token): JsonResponse
    {
        return $this->userService->activate($token);
    }
    public function login(LoginRequest $request): JsonResponse
    {
        $data = $request->validated();

        return $this->userService->loginUser($data);
    }
    public function refresh()
    {
        return $this->userService->refreshToken();
    }
    public function logout()
    {
        return $this->userService->logout();
    }

}
