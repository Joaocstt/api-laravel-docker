<?php
// app/Http/Middleware/ApiResponseMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class FormatApiResponse
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $statusCode = $response->getStatusCode();

        $message = match ($statusCode) {
            200 => 'Requisição bem-sucedida',
            201 => 'Recurso criado com sucesso',
            204 => 'Recurso deletado com sucesso',
            400 => 'Requisição malformada',
            404 => 'Recurso não encontrado',
            500 => 'Erro interno do servidor',
            default => 'Operação realizada com sucesso',
        };

        if ($statusCode >= 200 && $statusCode < 300) {
            return response()->json([
                'status' => 'success',
                'message' => $message,
                'data' => $response->getData(),
            ]);
        }

        return $response;
    }
}
