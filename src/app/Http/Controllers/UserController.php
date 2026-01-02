<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\SwaggerUserDocs;
use App\Http\Requests\UserRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Exception;

class UserController extends Controller
{
    use SwaggerUserDocs;
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function store(UserRequest $request): JsonResponse
    {
        try {
            $result = $this->userService->createUser($request->validated());

            return response()->json($result, 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao processar requisição',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function index(): JsonResponse
    {
        try {
            $result = $this->userService->getAllUsers();

            return response()->json($result);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar usuários',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            $result = $this->userService->getUserById($id);

            return response()->json($result);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar usuário',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function healthCheck(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'service' => 'ms-producer',
            'status' => 'running',
            'timestamp' => now()->toIso8601String()
        ]);
    }
}
