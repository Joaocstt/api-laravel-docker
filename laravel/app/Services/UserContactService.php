<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class UserContactService
{
    public function showMyContacts(): JsonResponse
    {
        try {
            $user = auth()->user()->load('contacts');

            if ($user->contacts->isEmpty()) {
                return response()->json(['error' => 'Você não possui contato.'], 404);
            }

            return response()->json(['contatos' => $user->contacts], 200);
        }
        catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function createContact(array $data): JsonResponse
    {
        DB::beginTransaction();

        try
        {
            $userLogged = auth()->user();

            if(!$userLogged) {
                return response()->json(['error' => 'Usuário não está autenticado|Token inválido.'], 401);
            }

            $userLogged->contacts()->create($data);

            DB::commit();
            return response()->json(['message' => 'Contato criado com sucesso!'], 201);
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 400);
        }

    }

    public function updatedContact(int $id, array $data): JsonResponse
    {
        try {
            $userLogged = auth()->user()->load('contacts');

            $contact = $userLogged->contacts()->find($id);

            if (!$contact) {
                return response()->json(['error' => 'Contato não encontrado.'], 404);
            }

            $contact->update($data);

            return response()->json(['message' => 'Contato atualizado com sucesso!'], 200);

        }
        catch (\Exception $e)
        {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
    public function deleteContact(int $id): JsonResponse
    {
        try {
            $userLogged = auth()->user();

            $contact = $userLogged->contacts()->find($id);

            if (!$contact) {
                return response()->json(['error' => 'Contato não encontrado.'], 404);
            }

            $contact->delete();

            return response()->json(['message' => 'Contato removido com sucesso!'], 200);
        }
        catch (\Exception $e)
        {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
