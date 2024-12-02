<?php

namespace App\Services;

use App\Mail\ActivationMail;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserService
{
    public function __construct(protected AuthServiceToken $authServiceToken) {}
    public function registerUser(array $data): JsonResponse
    {
        DB::beginTransaction();

        try {
            $user = User::query()->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'activation_token' => Str::random(60),
                'is_active' => false,
            ]);

            $activationLink = route('activate', ['token' => $user->activation_token]);

            Mail::to($user->email)->send(new ActivationMail($activationLink, $user->name));

            DB::commit();

            return response()->json(data: [
                'message' => 'Cadastro realizado com sucesso! Verifique seu e-mail para ativar sua conta.'
            ], status: 201);

        }
        catch (Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
    public function loginUser(array $data): JsonResponse
    {
        try {
            $credentials = [
                'email' => $data['email'],
                'password' => $data['password'],
            ];

            if (!$token = auth('api')->attempt($credentials)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $user = User::query()->where('email', $data['email'])->first();

            if (!$user || !$user->is_active) {
                return response()->json(['error' => 'Sua conta ainda não foi ativada. Verifique seu e-mail.'], 400);
            }

            return $this->authServiceToken->respondWithToken($token);

        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
    public function activate(string $token): JsonResponse
    {
        $user = User::query()->where('activation_token', $token)->first();

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Token does not exist.']);
        }

        if($user->is_active) {
            return response()->json(['status' => 'error', 'message' => 'Token is already active.']);
        }

        $user->is_active = true;
        $user->activation_token = null;
        $user->save();

        return response()->json(['status' => 'success', 'message' => 'Account activated.']);
    }

    public function refreshToken(): JsonResponse
    {
        return $this->authServiceToken->respondWithToken(auth('api')->refresh());
    }
    public function logout(): JsonResponse
    {
        auth()->logout();

        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json(['message' => 'Usuário deslogado com sucesso.']);
    }
}
