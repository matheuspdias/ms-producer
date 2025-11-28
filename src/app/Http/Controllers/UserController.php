<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Exception;

class UserController extends Controller
{
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
